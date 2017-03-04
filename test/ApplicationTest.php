<?php
namespace F3\Tracker;

use F3\Tracker\Test\GuzzleFactory;
use F3\Tracker\Web\Application;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class ApplicationTest extends TestCase
{
    public function testGetUuid()
    {
        $client = $this->createClient();
        $http_response = $client->post('/sessions');
        $this->assertEquals(200, $http_response->getStatusCode());
        $this->assertEquals('application/vnd.api+json', $http_response->getHeader('Content-Type')[0]);
        $api_response = new JsonApiResponse($http_response);
        $this->assertRegExp('/' . Uuid::VALID_PATTERN . '/', $api_response->document()->primaryResource()->id());
    }

    protected function createClient(): Client
    {
        return (new GuzzleFactory())->createClient($this->createApplication());
    }

    private function createApplication(): Application
    {
        return new Application();
    }
}
