<?php
use F3\Commuter\Web\LocationsController;
use Pimple\Container;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;

return function (Container $container, array $config) {
    $silex = new Application([
        'debug' => $config['debug'],
        'controller.locations' => function () use ($container) {
            return new LocationsController($container['application']);
        },
    ]);

    $silex->register(new ServiceControllerServiceProvider());
    $silex->post('/api/locations', 'controller.locations:addLocation');
    $silex->get('/api/current_locations', 'controller.locations:getCurrentLocations');
    $silex->error(function (Throwable $e) {
        error_log($e);
    });
    $container['web_app'] = $silex;
};
