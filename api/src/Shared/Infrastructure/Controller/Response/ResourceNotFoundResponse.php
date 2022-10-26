<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\Response;

use App\Core\Util\TypeInspector;

class ResourceNotFoundResponse extends NotFoundResponse
{
    public function __construct(string $resourceClass, string $resourceId, array $headers = [], bool $json = false)
    {
        parent::__construct(self::getMessage($resourceClass, $resourceId), $headers, $json);
    }

    private static function getMessage(string $resourceClass, string $resourceId): string
    {
        return sprintf(
            'Resource %s of id "%s" not found',
            TypeInspector::getClassName($resourceClass),
            $resourceId
        );
    }
}
