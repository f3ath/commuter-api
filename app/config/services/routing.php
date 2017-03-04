<?php
use F3\Tracker\Web\Serializer\SessionSerializer;
use Silex\Application;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;

return function (Application $app) {
    $app->post('/sessions', function () {
        return new Document(
            new Resource(
                \Ramsey\Uuid\Uuid::uuid4(),
                new SessionSerializer()
            )
        );
    });
};
