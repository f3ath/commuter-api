<?php
use F3\Commuter\Web\LocationsController;
use F3\Commuter\Web\MapController;
use Pimple\Container;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;

return function (Container $container, array $config) {
    $silex = new Application([
        'debug' => $config['debug'],
        'controller.locations' => function () use ($container) {
            return new LocationsController($container['application']);
        },
        'controller.map' => function ($silex) use ($container, $config) {
            return new MapController($silex['twig'], $config['map']['key']);
        },
    ]);

    $silex->register(new ServiceControllerServiceProvider());
    $silex->register(new TwigServiceProvider(), ['twig.path' => __DIR__ . '/../../view/web']);
    $silex->error(function (Throwable $e) { error_log($e); });

    $silex->get('/', 'controller.map:redirectToRandomMap');
    $silex->get('/map/{map_name}', 'controller.map:renderMap');
    $silex->post('/api/v0/map/{map_id}/locations', 'controller.locations:addLocation');
    $silex->get('/api/v0/map/{map_id}/locations', 'controller.locations:getCurrentLocations');
    $container['web_app'] = $silex;
};
