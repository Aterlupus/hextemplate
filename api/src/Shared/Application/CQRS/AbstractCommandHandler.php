<?php
declare(strict_types=1);

namespace App\Shared\Application\CQRS;

use App\Shared\Domain\AbstractDomainEntity;
use App\Shared\Domain\MissingEntityException;

abstract class AbstractCommandHandler implements CommandHandlerInterface
{
    /**
     * @throws MissingEntityException
     */
    protected static function assertEntityNotNull(
        string $entityId,
        string $entityClass,
        ?AbstractDomainEntity $domainEntity
    ): void {
        if (null === $domainEntity) {
            throw new MissingEntityException($entityId, $entityClass);
        }
    }
}
