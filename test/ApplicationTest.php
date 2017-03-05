<?php
namespace F3\Commuter;

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
        return (new Test\GuzzleFactory())->createClient($this->createApplication());
    }

    private function createApplication(): Web\Application
    {
        return new Web\Application();
    }
}
