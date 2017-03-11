<?php
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

    public function testSet()
    {
        $this->app->expects($this->once())
            ->method('set')
            ->with('test_key', ['lat' => 1.23, 'lng' => -3.21]);
        $response = $this->web_client->post(
            '/locations',
            [
                'json' => [
                    'data' => [
                        'type'       => 'locations',
                        'id' => 'test_key',
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

    public function testGetAll()
    {
        $this->app->expects($this->once())
            ->method('getAll')
            ->willReturn(
                [
                    ['lat' => 1.23, 'lng' => -3.21],
                ]
            );
        $response = $this->web_client->get('/current_locations');
        self::assertStatusCode(200, $response);
        $json = json_decode($response->getBody());
        $data = $json->data;
        self::assertEquals('current_locations', $data->type);
        self::assertNotEmpty($data->id);
        self::assertEquals(
            [
                (object) ['lat' => 1.23, 'lng' => -3.21],
            ],
            $data->attributes->locations
        );
    }

    private static function assertStatusCode(int $code, ResponseInterface $response): void
    {
        self::assertEquals($code, $response->getStatusCode());
    }
}
