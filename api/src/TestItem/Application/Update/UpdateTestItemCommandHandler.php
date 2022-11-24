<?php
declare(strict_types=1);

namespace App\TestItem\Application\Update;

use App\Core\Util\StackTraceHelper;
use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\TestCollection\Domain\TestCollectionId;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemAmount;
use App\TestItem\Domain\TestItemDescription;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateTestItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    public function __invoke(UpdateTestItemCommand $command): void
    {
        $testItem = $this->testItemRepository->get(new TestItemId($command->getId()));
        //TODO: create proper assert for Entity existence
        Assert::notNull($testItem, sprintf("Entity %s of id %s not found. %s", TestItem::class, $command->getId(), StackTraceHelper::getStackTraceMessage()));

        $testItem->update(
            new TestItemDescription($command->getDescription()),
            new TestItemAmount($command->getAmount()),
            new TestCollectionId($command->getTestCollectionId())
        );

        $this->testItemRepository->save($testItem);
    }
}
