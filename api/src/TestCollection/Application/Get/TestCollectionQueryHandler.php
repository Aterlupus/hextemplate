<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Get;

use App\Shared\Application\CQRS\QueryHandlerInterface;
use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionRepositoryInterface;

class TestCollectionQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TestCollectionRepositoryInterface $testCollectionRepository
    ) {}

    public function __invoke(TestCollectionQuery $query): ?TestCollection
    {
        return $this->testCollectionRepository->get(
            new TestCollectionId($query->getTestCollectionId())
        );
    }
}
