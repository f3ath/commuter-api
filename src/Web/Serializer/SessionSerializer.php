<?php
namespace F3\Commuter\Web\Serializer;

use Tobscure\JsonApi\AbstractSerializer;

class SessionSerializer extends AbstractSerializer
{
    public function __construct()
    {
        $this->type = 'session';
    }

    public function getId($session): string
    {
        return $session;
    }

    public function getAttributes($model, array $fields = null)
    {
        return ['generated' => time()];
    }
}
