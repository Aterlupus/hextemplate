<?php
declare(strict_types=1);

namespace App\TestItem\Application\Get;

use App\Shared\Application\CQRS\QueryHandlerInterface;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class TestItemQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    public function __invoke(TestItemQuery $query): ?TestItem
    {
        return $this->testItemRepository->get(
            new TestItemId($query->getTestItemId())
        );
    }
}
