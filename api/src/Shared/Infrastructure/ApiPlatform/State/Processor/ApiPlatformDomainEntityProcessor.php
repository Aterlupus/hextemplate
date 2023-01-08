<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Core\Util\StringParser;
use App\Shared\Application\CQRS\CommandBusInterface;
use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\QueryBusInterface;
use App\Shared\Domain\AbstractDomainEntity;
use App\Shared\Infrastructure\ApiPlatform\State\AbstractApiPlatformDomainEntityOperator;

class ApiPlatformDomainEntityProcessor extends AbstractApiPlatformDomainEntityOperator implements ProcessorInterface
{
    private const ENTITY_COMMAND_CLASS_FORMAT = 'App\\%s\\Application\\%s\\%s%sCommand';

    private const METHOD_TO_ACTION = [
        'POST' => 'Create',
        'PUT' => 'Update',
        'DELETE' => 'Delete',
    ];


    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus
    ) {
        parent::__construct($this->queryBus);
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?AbstractDomainEntity
    {
        $domain = $operation->getShortName();
        $action = self::getActionForMethod($operation);

        $commandClass = self::getEntityCommandClass($domain, $action);
        $command = $commandClass::createFromArray($data->jsonSerialize());
        $this->dispatchCommand($command);

        return $this->getDomainEntity($domain, $command->getId());
    }

    private static function getActionForMethod(Operation $operation): string
    {
        if (self::isDefaultName($operation->getName())) {
            return self::METHOD_TO_ACTION[$operation->getMethod()];
        } else {
            return ucfirst($operation->getName());
        }
    }

    private static function isDefaultName(string $operationName): bool
    {
        return StringParser::startsWith($operationName, '_');
    }

    private static function getEntityCommandClass(string $domain, string $action): CommandInterface|string
    {
        return sprintf(self::ENTITY_COMMAND_CLASS_FORMAT, $domain, $action, $action, $domain);
    }

    protected function dispatchCommand(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
