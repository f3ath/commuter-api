<?php
namespace F3\Commuter\Web;

use F3\Commuter\Application;
use F3\Commuter\Web\JsonApi\Response;
use Symfony\Component\HttpFoundation\Request;

class LocationsController
{
    private $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function addLocation(Request $request)
    {
        $json = json_decode($request->getContent());
        $location = [
            'lat' => $json->data->attributes->lat,
            'lng' => $json->data->attributes->lng
        ];
        $this->application->add($location);
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function getCurrentLocations()
    {
        $response = [
            'data' => [
                'type' => 'current_locations',
                'id' => (string) time(),
                'attributes' => [
                    'locations' => $this->application->getAll()
                ]
            ]
        ];
        return new Response($response);
    }
}
