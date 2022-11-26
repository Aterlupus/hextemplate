<?php
declare(strict_types=1);

namespace App\TestItem\Application\Update;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\Shared\Domain\MissingEntityException;
use App\TestCollection\Domain\TestCollectionId;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemAmount;
use App\TestItem\Domain\TestItemDescription;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class UpdateTestItemCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    /**
     * @throws MissingEntityException
     */
    public function __invoke(UpdateTestItemCommand $command): void
    {
        $testItem = $this->testItemRepository->get(new TestItemId($command->getId()));
        self::assertEntityNotNull($command->getId(), TestItem::class, $testItem);

        $testItem->update(
            new TestItemDescription($command->getDescription()),
            new TestItemAmount($command->getAmount()),
            new TestCollectionId($command->getTestCollectionId())
        );

        $this->testItemRepository->save($testItem);
    }
}
