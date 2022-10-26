<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller;

use App\Shared\Application\CQRS\CommandBusInterface;
use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\QueryBusInterface;
use App\Shared\Application\CQRS\QueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends SymfonyAbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus
    ) {}

    protected function dispatchCommand(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }

    protected function dispatchQuery(QueryInterface $query): mixed
    {
        return $this->queryBus->dispatch($query);
    }

    protected static function getRequestContent(Request $request): array
    {
        return json_decode($request->getContent(), true) ?? [];
    }
}
