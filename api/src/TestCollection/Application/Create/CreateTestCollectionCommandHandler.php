<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Create;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionName;
use App\TestCollection\Domain\TestCollectionRepositoryInterface;
use App\TestCollection\Domain\TestCollectionTestItemsIds;

class CreateTestCollectionCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestCollectionRepositoryInterface $testCollectionRepository
    ) {}

    public function __invoke(CreateTestCollectionCommand $command): void
    {
        $testCollection = new TestCollection(
            new TestCollectionId($command->getId()),
            new TestCollectionName($command->getName()),
            new TestCollectionTestItemsIds(),
        );

        $this->testCollectionRepository->save($testCollection);
    }
}
