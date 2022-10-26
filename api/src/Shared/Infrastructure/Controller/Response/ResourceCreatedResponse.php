<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\Response;

use Symfony\Component\HttpFoundation\Response;

class ResourceCreatedResponse extends AbstractHttpResponse
{
    protected static function getResponseCode(): int
    {
        return Response::HTTP_CREATED;
    }

    public function __construct(string $resourceId, array $headers = [], bool $json = false)
    {
        parent::__construct(['id' => $resourceId], $headers, $json);
    }
}
