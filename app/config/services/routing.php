<?php
use F3\Commuter\Web\Serializer\SessionSerializer;
use Ramsey\Uuid\Uuid;
use Silex\Application;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;

return function (Application $app) {
    $app->post('/sessions', function () {
        return new Document(
            new Resource(
                Uuid::uuid4(),
                new SessionSerializer()
            )
        );
    });
};
