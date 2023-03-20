<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\EventSubscriber;

use App\Shared\Infrastructure\Controller\Response\AbstractHttpResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

class ExceptionToHttpResponseMapper
{
    public static function getResponse(Throwable $exception): ?JsonResponse
    {
        if (self::isQualifiedExceptionEvent($exception)) {
            return self::getResponseForException($exception->getPrevious());
        } else {
            return null;
        }
    }

    private static function isQualifiedExceptionEvent(Throwable $exception): bool
    {
        return self::isHandlerFailedException($exception)
            && self::isQualifiedException($exception->getPrevious());
    }

    private static function isHandlerFailedException(Throwable $exception): bool
    {
        return $exception instanceof HandlerFailedException;
    }

    private static function isQualifiedException(Throwable $exception): bool
    {
        return ExceptionToHttpResponseSuit::hasKey($exception::class);
    }

    private static function getResponseForException(Throwable $exception): AbstractHttpResponse
    {
        $responseClass = ExceptionToHttpResponseSuit::getValue($exception::class);
        return new $responseClass($exception->getMessage());
    }
}
