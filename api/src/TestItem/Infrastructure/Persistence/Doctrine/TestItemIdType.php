<?php
declare(strict_types=1);

namespace App\TestItem\Infrastructure\Persistence\Doctrine;

use App\Shared\Infrastructure\Persistence\Doctrine\UuidType;
use App\TestItem\Domain\TestItemId;

class TestItemIdType extends UuidType
{
    protected function getTypeClass(): string
    {
        return TestItemId::class;
    }
}
