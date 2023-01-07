<?php
declare(strict_types=1);

namespace App\TestItem\Application\Activate;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\Shared\Application\Exception\MissingEntityException;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class ActivateTestItemCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    /**
     * @throws MissingEntityException
     */
    public function __invoke(ActivateTestItemCommand $command): void
    {
        $testItem = $this->testItemRepository->get(new TestItemId($command->getId()));
        self::assertEntityNotNull($command->getId(), TestItem::class, $testItem);

        $testItem->activate();

        $this->testItemRepository->save($testItem);
    }
}
