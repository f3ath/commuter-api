<?php
use Silex\Application;

return function (Application $app, array $config) {
    $app['debug'] = $config['debug'];
    $app->error(function (Throwable $e) {
        error_log($e);
    });

    $app['pdo'] = function () use ($config) {
        return new PDO($config['storage']['dsn']);
    };
    $app['location_storage'] = function () use ($app) {
        $pdo = $app['pdo'];
        $pdo->exec('CREATE TABLE IF NOT EXISTS locations (id TEXT PRIMARY KEY, lat REAL, lng REAL, updated INT)');
        //$pdo->exec('DELETE FROM locations');
        return new \F3\Commuter\LocationStorage($pdo);
    };
};
