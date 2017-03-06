<?php
namespace F3\Commuter\Web;

use F3\Commuter\Test\GuzzleFactory;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class ApplicationTest extends TestCase
{
    public function testLocationCreationAndFetching()
    {
        $client = $this->createClient();
        $http_response = $client->post(
            '/locations',
            [
                'json' => [
                    'data' => [
                        'type' => 'locations',
                        'attributes' => [
                            'lat' => 12.345,
                            'lng' => -1.2345,
                        ],
                    ],
                ],
            ]
        );
        $this->checkHttpResponse($http_response);
        $api_response = new JsonApiResponse($http_response);
        $this->assertRegExp(
            '/' . Uuid::VALID_PATTERN . '/',
            $api_response->document()->primaryResource()->id(),
            'Location UUID must be created'
        );
        $this->assertEquals(
            [
                'lat' => 12.345,
                'lng' => -1.2345,
            ],
            $api_response->document()->primaryResource()->attributes(),
            'Attributes must contain coordinates'
        );

        $http_response = $client->get('/locations');
        $this->checkHttpResponse($http_response);
        $api_response = new JsonApiResponse($http_response);
        $locations = $api_response->document()->primaryResources();

        $this->assertEquals(
            [
                'lat' => 12.345,
                'lng' => -1.2345,
            ],
            $locations[0]->attributes(),
            'Attributes must contain coordinates'
        );
    }

    protected function createClient(): Client
    {
        return (new GuzzleFactory())->createClient($this->createApplication());
    }

    private function createApplication(): Application
    {
        return new Application();
    }

    private function checkHttpResponse(ResponseInterface $http_response): void
    {
        $this->assertEquals(
            200,
            $http_response->getStatusCode(),
            'Status code must be OK'
        );
        $this->assertEquals(
            ['application/vnd.api+json'],
            $http_response->getHeader('Content-Type'),
            'Content-Type must be as expected'
        );
    }
}
