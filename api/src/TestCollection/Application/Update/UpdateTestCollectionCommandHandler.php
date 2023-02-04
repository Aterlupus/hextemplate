<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Update;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\Shared\Application\Exception\MissingEntityException;
use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionName;
use App\TestCollection\Domain\TestCollectionRepositoryInterface;
use App\TestCollection\Domain\TestCollectionTestItemsIds;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class UpdateTestCollectionCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestCollectionRepositoryInterface $testCollectionRepository,
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    /**
     * @throws MissingEntityException
     */
    public function __invoke(UpdateTestCollectionCommand $command): void
    {
        $testCollection = $this->testCollectionRepository->get(new TestCollectionId($command->getId()));
        self::assertEntityNotNull($command->getId(), TestCollection::class, $testCollection);

        foreach ($command->getTestItemsIds() as $testItemId) {
            $testItem = $this->testItemRepository->get(new TestItemId($testItemId));
            self::assertEntityNotNull($testItemId, TestItem::class, $testItem);
        }

        $testCollection->update(
            new TestCollectionName($command->getName()),
            new TestCollectionTestItemsIds($command->getTestItemsIds())
        );

        $this->testCollectionRepository->save($testCollection);
    }
}
