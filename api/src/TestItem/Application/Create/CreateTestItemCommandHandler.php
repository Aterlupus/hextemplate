<?php
declare(strict_types=1);

namespace App\TestItem\Application\Create;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\Shared\Application\Exception\MissingEntityException;
use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionRepositoryInterface;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemAmount;
use App\TestItem\Domain\TestItemDescription;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemIsActive;
use App\TestItem\Domain\TestItemRepositoryInterface;

class CreateTestItemCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository,
        private readonly TestCollectionRepositoryInterface $testCollectionRepository
    ) {}

    /**
     * @throws MissingEntityException
     */
    public function __invoke(CreateTestItemCommand $command): void
    {
        $testCollection = $this->testCollectionRepository->get(new TestCollectionId($command->getTestCollectionId()));
        self::assertEntityNotNull($command->getTestCollectionId(), TestCollection::class, $testCollection);

        $testItem = new TestItem(
            new TestItemId($command->getId()),
            new TestItemDescription($command->getDescription()),
            new TestItemAmount($command->getAmount()),
            new TestItemIsActive($command->getIsActive()),
            new TestCollectionId($command->getTestCollectionId())
        );

        $this->testItemRepository->save($testItem);
    }
}
