<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractHttpResponse extends JsonResponse
{
    abstract protected static function getResponseCode(): int;

    public function __construct(mixed $data = null, array $headers = [], bool $json = false)
    {
        parent::__construct($data, static::getResponseCode(), $headers, $json);
    }
}
