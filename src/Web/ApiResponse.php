<?php
namespace F3\Commuter\Web;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct(\JsonSerializable $data, int $status = self::HTTP_OK)
    {
        parent::__construct($data, $status, ['Content-Type' => 'application/vnd.api+json']);
    }
}
