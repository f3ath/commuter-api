<?php
use F3\Commuter\Application;
use Pimple\Container;

return function (Container $container, array $config) {
    $container['pdo'] = function () use ($config) {
        return new PDO($config['storage']['dsn'], null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    };
    $container['application'] = function ($app) {
        return new Application($app['pdo'], new DateTimeImmutable());
    };
};
