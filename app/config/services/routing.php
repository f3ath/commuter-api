<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

return function (Application $app) {
    $app->get('/', function() {
        return file_get_contents(__DIR__ . '/../../../public/index.html');
    });

    $app->post('/locations', function (Request $request) use ($app) {
        $json = json_decode($request->getContent());
        $location = \F3\Commuter\Location::fromLatLng(
            $json->data->attributes->lat,
            $json->data->attributes->lng
        );
        $json->data->id = (string) $location->getId();
        $app['location_storage']->add($location);
        return new \F3\Commuter\Web\ApiResponse($json);
    });

    $app->get('/locations', function () use ($app) {
        $response = [
            'data' => null
        ];
        /** @var \F3\Commuter\Location $location */
        foreach ($app['location_storage']->fetchRecent() as $location) {
            $response['data'][] = [
                'type' => 'locations',
                'id' => $location->getId(),
                'attributes' => [
                    'lat' => $location->getLat(),
                    'lng' => $location->getLng(),
                ],
            ];
        }
        return new \F3\Commuter\Web\ApiResponse($response);
    });
};
