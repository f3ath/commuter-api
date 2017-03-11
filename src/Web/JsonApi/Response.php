<?php
namespace F3\Commuter\Web\JsonApi;

use Symfony\Component\HttpFoundation\JsonResponse;

class Response extends JsonResponse
{
    const CONTENT_TYPE = 'application/vnd.api+json';

    public function __construct($data, int $status = self::HTTP_OK)
    {
        parent::__construct($data, $status, ['Content-Type' => self::CONTENT_TYPE]);
    }
}
