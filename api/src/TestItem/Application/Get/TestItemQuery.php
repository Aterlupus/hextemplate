<?php
declare(strict_types=1);

namespace App\TestItem\Application\Get;

use App\Shared\Application\CQRS\QueryInterface;

class TestItemQuery implements QueryInterface
{
    public function __construct(
        private readonly string $testItemId
    ) {}

    public function getTestItemId(): string
    {
        return $this->testItemId;
    }
}
