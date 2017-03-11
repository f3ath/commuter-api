<?php
use F3\Commuter\Application;
use Pimple\Container;

return function (Container $container) {
    $container['application'] = function ($app) {
        return new Application($app['storage']);
    };
};
