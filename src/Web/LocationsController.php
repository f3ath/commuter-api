<?php
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

    public function addLocation(Request $request)
    {
        $json = json_decode($request->getContent());
        $this->commuter_app->set(
            $json->data->id,
            [
                'lat' => $json->data->attributes->lat,
                'lng' => $json->data->attributes->lng
            ]
        );
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function getCurrentLocations()
    {
        return new Response([
            'data' => [
                'type' => 'current_locations',
                'id' => (string) time(),
                'attributes' => [
                    'locations' => $this->commuter_app->getAll()
                ]
            ]
        ]);
    }
}
