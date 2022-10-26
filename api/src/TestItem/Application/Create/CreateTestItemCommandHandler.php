<?php
declare(strict_types=1);

namespace App\TestItem\Application\Create;

use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\TestCollection\Domain\TestCollectionId;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemAmount;
use App\TestItem\Domain\TestItemDescription;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class CreateTestItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    public function __invoke(CreateTestItemCommand $command): void
    {
        $testItem = new TestItem(
            new TestItemId($command->getId()),
            new TestItemDescription($command->getDescription()),
            new TestItemAmount($command->getAmount()),
            new TestCollectionId($command->getTestCollectionId())
        );

        $this->testItemRepository->save($testItem);
    }
}
