<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Shared\Application\CQRS\CommandBusInterface;
use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\QueryBusInterface;
use App\Shared\Domain\AbstractDomainEntity;
use App\Shared\Infrastructure\ApiPlatform\State\AbstractApiPlatformDomainEntityOperator;

class ApiPlatformDomainEntityProcessor extends AbstractApiPlatformDomainEntityOperator implements ProcessorInterface
{
    private const ENTITY_COMMAND_CLASS_FORMAT = 'App\\%s\\Application\\Create\\Create%sCommand';


    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct($this->queryBus);
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?AbstractDomainEntity
    {
        $domain = $operation->getShortName();

        $commandClass = self::getCreateCommandClass($domain);
        $command = $commandClass::createFromArray($data->jsonSerialize());
        $this->dispatchCommand($command);

        return $this->getDomainEntity($domain, $command->getId());
    }

    private static function getCreateCommandClass(string $domain): CommandInterface|string
    {
        return sprintf(self::ENTITY_COMMAND_CLASS_FORMAT, $domain, $domain);
    }

    protected function dispatchCommand(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
