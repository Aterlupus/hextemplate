<?php
declare(strict_types=1);

namespace App\TestItem\Application\GetCollection;

use App\Shared\Application\CQRS\QueryHandlerInterface;
use App\TestItem\Domain\TestItemRepositoryInterface;

class TestItemsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    public function __invoke(TestItemsQuery $query): array
    {
        return $this->testItemRepository->getMany(
            $query->getTestItemIds()
        );
    }
}
