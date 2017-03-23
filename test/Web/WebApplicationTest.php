<?php
declare(strict_types = 1);

namespace F3\Commuter\Web;

use F3\Commuter\Application;
use F3\Commuter\DI;
use F3\Commuter\Test\GuzzleFactory;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class WebApplicationTest extends TestCase
{
    private $app;

    /**
     * @var Client
     */
    private $web_client;


    protected function setUp()
    {
        $this->app = $this->createMock(Application::class);
        $di = new DI('test');
        $di['application'] = $this->app;
        $this->web_client = (new GuzzleFactory())->createClient($di['web_app']);
    }

    public function testSetPoint()
    {
        $this->app->expects($this->once())
            ->method('addLocation')
            ->with(
                'test_map',
                [
                    'id'          => 'test_key',
                    'lat'         => 1.23,
                    'lng'         => -3.21,
                    'expires'     => 60,
                    'description' => null,
                    'type'        => null,
                ]
            );
        $response = $this->web_client->post(
            '/api/v0/map/test_map/locations',
            [
                'json' => [
                    'data' => [
                        'type'       => 'locations',
                        'id'         => 'test_key',
                        'attributes' => [
                            'lat' => 1.23,
                            'lng' => -3.21,
                        ],
                    ],
                ],
            ]
        );
        self::assertStatusCode(204, $response);
    }

    public function testSetSpecialPoint()
    {
        $this->app->expects($this->once())
            ->method('addLocation')
            ->with(
                'test_map',
                [
                    'id'          => 'test_key',
                    'lat'         => 1.23,
                    'lng'         => -3.21,
                    'expires'     => 180,
                    'description' => 'my special point',
                    'type'        => 'special',
                ]
            );
        $response = $this->web_client->post(
            '/api/v0/map/test_map/locations',
            [
                'json' => [
                    'data' => [
                        'type'       => 'locations',
                        'id'         => 'test_key',
                        'attributes' => [
                            'lat'         => 1.23,
                            'lng'         => -3.21,
                            'expires'     => 180,
                            'description' => 'my special point',
                            'type'        => 'special',
                        ],
                    ],
                ],
            ]
        );
        self::assertStatusCode(204, $response);
    }

    public function testGetAll()
    {
        $this->app->expects($this->once())
            ->method('getLocations')
            ->with('test_map')
            ->willReturn(
                [
                    ['lat' => 1.23, 'lng' => -3.21],
                ]
            );
        $response = $this->web_client->get('/api/v0/map/test_map/locations');
        self::assertStatusCode(200, $response);
        $json = json_decode((string)$response->getBody());
        $data = $json->data;
        self::assertEquals('current_locations', $data->type);
        self::assertNotEmpty($data->id);
        self::assertEquals(
            [
                (object)['lat' => 1.23, 'lng' => -3.21],
            ],
            $data->attributes->locations
        );
    }

    private static function assertStatusCode(int $code, ResponseInterface $response): void
    {
        self::assertEquals($code, $response->getStatusCode());
    }
}
