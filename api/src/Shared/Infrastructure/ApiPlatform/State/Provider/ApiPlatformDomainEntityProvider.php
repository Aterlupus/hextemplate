<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Shared\Infrastructure\ApiPlatform\State\AbstractApiPlatformDomainEntityOperator;
use App\Shared\Infrastructure\Controller\Response\ResourceNotFoundResponse;

class ApiPlatformDomainEntityProvider extends AbstractApiPlatformDomainEntityOperator implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|iterable|null
    {
        $id = $uriVariables['id'];
        $domain = $operation->getShortName();
        $entity = $this->getDomainEntity($domain, (string) $id);

        if (null !== $entity) {
            $resourceClass = $operation->getClass();
            return $resourceClass::fromModel($entity);
        } else {
            $entityClass = self::getDomainEntityClass($domain);
            return new ResourceNotFoundResponse($entityClass, (string) $id);
        }
    }
}
