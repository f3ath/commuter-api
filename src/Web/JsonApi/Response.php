<?php
declare(strict_types=1);

namespace F3\Commuter\Web\JsonApi;

use JsonApiPhp\JsonApi\Document\Document;
use Symfony\Component\HttpFoundation\JsonResponse;

class Response extends JsonResponse
{
    public function __construct($data, int $status = self::HTTP_OK)
    {
        parent::__construct($data, $status, ['Content-Type' => Document::MEDIA_TYPE]);
    }
}
