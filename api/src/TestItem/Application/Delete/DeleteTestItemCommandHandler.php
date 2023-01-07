<?php
declare(strict_types=1);

namespace App\TestItem\Application\Delete;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\Shared\Application\Exception\MissingEntityException;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class DeleteTestItemCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    /**
     * @throws MissingEntityException
     */
    public function __invoke(DeleteTestItemCommand $command): void
    {
        $testItem = $this->testItemRepository->get(new TestItemId($command->getId()));
        self::assertEntityNotNull($command->getId(), TestItem::class, $testItem);

        $this->testItemRepository->delete($testItem);
    }
}
