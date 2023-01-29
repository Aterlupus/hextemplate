<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Core\Uuid;
use App\Shared\Infrastructure\ApiPlatform\State\AbstractApiPlatformDomainEntityOperator;
use App\Shared\Infrastructure\Controller\Response\ResourceNotFoundResponse;

class ApiPlatformDomainEntityProvider extends AbstractApiPlatformDomainEntityOperator implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|iterable|null
    {
        $domain = $operation->getShortName();

        if (self::isGettingMany($operation, $uriVariables)) {
            return $this->getDomainEntities($domain, self::getIdsFromContext($context));
        } else {
            $id = $uriVariables['id'];
            $entity = $this->getDomainEntity($domain, (string) $id);

            if (null !== $entity) {
                $resourceClass = $operation->getClass();
                if (self::isUpdating($operation)) {
                    return self::getEmptyEntity($resourceClass, $uriVariables);
                } else {
                    return $resourceClass::fromModel($entity);
                }
            } else {
                $entityClass = self::getDomainEntityClass($domain);
                return new ResourceNotFoundResponse($entityClass, (string) $id);
            }
        }
    }

    private static function getIdsFromContext(array $context): ?array
    {
        if (isset($context['filters']['id'])) {
            if (is_array($context['filters']['id'])) {
                return $context['filters']['id'];
            } else {
                return [$context['filters']['id']];
            }
        } else {
            return null;
        }
    }

    private static function isGettingMany(Operation $operation, array $uriVariables): bool
    {
        return 'GET' === $operation->getMethod() && false === isset($uriVariables['id']);
    }

    private static function isUpdating(Operation $operation): bool
    {
        return 'PUT' === $operation->getMethod();
    }

    //Api platform needs to get empty resource for PUT request in order to validate request properly
    private static function getEmptyEntity(string $resourceClass, array $uriVariables): object
    {
        return new $resourceClass(new Uuid($uriVariables['id']));
    }
}
