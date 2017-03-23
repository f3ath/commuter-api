<?php
declare(strict_types = 1);

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
        $data = json_decode($request->getContent())->data;
        $this->commuter_app->addLocation(
            $map_id,
            [
                'id'          => $data->id,
                'lat'         => $data->attributes->lat,
                'lng'         => $data->attributes->lng,
                'description' => $data->attributes->description ?? null,
                'expires'     => $data->attributes->expires ?? 60,
                'type'        => $data->attributes->type ?? null,
            ]
        );
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function getCurrentLocations(string $map_id)
    {
        return new Response([
            'data' => [
                'type'       => 'current_locations',
                'id'         => (string)time(),
                'attributes' => [
                    'locations' => $this->commuter_app->getLocations($map_id),
                ],
            ],
        ]);
    }
}
