<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\Response;

use Symfony\Component\HttpFoundation\Response;

class BadRequestResponse extends AbstractHttpResponse
{
    protected static function getResponseCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
