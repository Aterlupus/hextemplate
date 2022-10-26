<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Get;

use App\Shared\Application\CQRS\QueryInterface;

class TestCollectionQuery implements QueryInterface
{
    public function __construct(
        private readonly string $testCollectionId
    ) {}

    public function getTestCollectionId(): string
    {
        return $this->testCollectionId;
    }
}
