<?php
declare(strict_types=1);

namespace App\TestItem\Application\Delete;

use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class DeleteTestItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    public function __invoke(DeleteTestItemCommand $command): void
    {
        $testItem = $this->testItemRepository->get(new TestItemId($command->getId()));

        $this->testItemRepository->delete($testItem);
    }
}
