<?php
use F3\Commuter\ExpirableStorage;
use Pimple\Container;

return function (Container $container, array $config) {
    $container['pdo'] = function () use ($config) {
        return new PDO($config['storage']['dsn'], null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    };
    $container['storage'] = function () use ($container, $config) {
        return new ExpirableStorage($container['pdo'], $config['storage']['lifetime']);
    };
};
