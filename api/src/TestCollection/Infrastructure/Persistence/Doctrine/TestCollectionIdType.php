<?php
declare(strict_types=1);

namespace App\TestCollection\Infrastructure\Persistence\Doctrine;

use App\Shared\Infrastructure\Persistence\Doctrine\UuidType;
use App\TestCollection\Domain\TestCollectionId;

class TestCollectionIdType extends UuidType
{
    protected function getTypeClass(): string
    {
        return TestCollectionId::class;
    }
}
