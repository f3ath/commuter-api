<?php
use F3\Commuter\ExpirableStorage;
use Pimple\Container;

return function (Container $container, array $config, string $env) {
    $container['pdo'] = function () use ($config) {
        return new PDO($config['storage']['dsn']);
    };
    $container['storage'] = function () use ($container, $config) {
        return new ExpirableStorage($container['pdo'], $config['storage']['lifetime']);
    };
};
