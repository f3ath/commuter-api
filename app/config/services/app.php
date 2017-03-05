<?php
use F3\Commuter\Web\ApiResponse;
use Silex\Application;

return function(Application $app) {
    $app->view(function (JsonSerializable $response) use ($app) {
        return new ApiResponse($response);
    });
};
