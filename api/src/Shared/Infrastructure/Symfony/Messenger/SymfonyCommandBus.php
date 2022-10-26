<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Messenger;

use App\Shared\Application\CQRS\CommandBusInterface;
use App\Shared\Application\CQRS\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyCommandBus implements CommandBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {}

    public function dispatch(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
