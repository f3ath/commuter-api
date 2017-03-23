<?php
declare(strict_types=1);

namespace F3\Commuter\Web;

use F3\Commuter\Application;
use F3\Commuter\Web\JsonApi\Response;
use Symfony\Component\HttpFoundation\Request;

class LocationsController
{
    private $commuter_app;

    public function __construct(Application $commuter_app)
    {
        $this->commuter_app = $commuter_app;
    }

    public function addLocation(Request $request, string $map_id)
    {
        $json = json_decode($request->getContent());
        $this->commuter_app->addLocation(
            $map_id,
            [
                'id' => $json->data->id,
                'lat' => $json->data->attributes->lat,
                'lng' => $json->data->attributes->lng,
                'expires' => 60,
            ]
        );
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function getCurrentLocations(string $map_id)
    {
        return new Response([
            'data' => [
                'type' => 'current_locations',
                'id' => (string) time(),
                'attributes' => [
                    'locations' => $this->commuter_app->getLocations($map_id)
                ]
            ]
        ]);
    }
}
