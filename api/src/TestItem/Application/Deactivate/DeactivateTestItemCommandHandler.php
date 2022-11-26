<?php
declare(strict_types=1);

namespace App\TestItem\Application\Deactivate;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\Shared\Domain\MissingEntityException;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class DeactivateTestItemCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    /**
     * @throws MissingEntityException
     */
    public function __invoke(DeactivateTestItemCommand $command): void
    {
        $testItem = $this->testItemRepository->get(new TestItemId($command->getId()));
        self::assertEntityNotNull($command->getId(), TestItem::class, $testItem);

        $testItem->deactivate();

        $this->testItemRepository->save($testItem);
    }
}
