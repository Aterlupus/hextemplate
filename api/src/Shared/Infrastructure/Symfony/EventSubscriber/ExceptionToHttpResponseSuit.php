<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\EventSubscriber;

use App\Core\Suit\AbstractAssocSuit;
use App\Shared\Application\Exception\MissingEntityException;
use App\Shared\Infrastructure\Controller\Response\NotFoundResponse;

class ExceptionToHttpResponseSuit extends AbstractAssocSuit
{
    const EXCEPTION_TO_HTTP_RESPONSE = [
        MissingEntityException::class => NotFoundResponse::class,
    ];
}
