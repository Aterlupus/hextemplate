<?php
declare(strict_types=1);

namespace App\TestItem\Application\GetCollection;

use App\Shared\Application\CQRS\QueryInterface;

class TestItemsQuery implements QueryInterface
{
    public function __construct(
        private readonly ?array $testItemIds = null
    ) {}

    public function getTestItemIds(): ?array
    {
        return $this->testItemIds;
    }
}
