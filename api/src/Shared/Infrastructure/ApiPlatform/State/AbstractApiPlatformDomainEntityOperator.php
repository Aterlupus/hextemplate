<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\State;

use App\Shared\Application\CQRS\QueryBusInterface;
use App\Shared\Application\CQRS\QueryInterface;
use App\Shared\Domain\AbstractDomainEntity;
use InvalidArgumentException;

abstract class AbstractApiPlatformDomainEntityOperator
{
    private const DOMAIN_ENTITY_CLASS_FORMAT = 'App\\%s\\Domain\\%s';

    private const ENTITY_QUERY_CLASS_FORMAT = 'App\\%s\\Application\\Get\\%sQuery';


    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    protected function getDomainEntity(string $domain, string $id): ?AbstractDomainEntity
    {
        $getQueryClass = self::getQueryClass($domain);
        self::assertQueryClassExists($getQueryClass);

        return $this->dispatchQuery(new $getQueryClass($id));
    }

    protected static function getDomainEntityClass(string $domain): string
    {
        return sprintf(self::DOMAIN_ENTITY_CLASS_FORMAT, $domain, $domain);
    }

    protected static function getQueryClass(string $domain): string
    {
        return sprintf(self::ENTITY_QUERY_CLASS_FORMAT, $domain, $domain);
    }

    protected static function assertQueryClassExists(string $getQueryClass): void
    {
        if (false === class_exists($getQueryClass)) {
            throw new InvalidArgumentException(sprintf('Missing Query class "%s"', $getQueryClass));
        }
    }

    protected function dispatchQuery(QueryInterface $query): mixed
    {
        return $this->queryBus->dispatch($query);
    }
}
