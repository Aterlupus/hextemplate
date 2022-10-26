<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Messenger;

use App\Shared\Application\CQRS\QueryBusInterface;
use App\Shared\Application\CQRS\QueryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SymfonyQueryBus implements QueryBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $queryBus
    ) {}

    public function dispatch(QueryInterface $query): mixed
    {
        $envelope = $this->queryBus->dispatch($query);
        /** @var HandledStamp $stamp */
        $stamp = $envelope->last(HandledStamp::class);

        return $stamp->getResult();
    }
}
