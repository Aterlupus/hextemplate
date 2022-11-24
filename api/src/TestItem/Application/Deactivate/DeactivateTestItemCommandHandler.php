<?php
declare(strict_types=1);

namespace App\TestItem\Application\Deactivate;

use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class DeactivateTestItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    public function __invoke(DeactivateTestItemCommand $command): void
    {
        $testItem = $this->testItemRepository->get(new TestItemId($command->getId()));
        //TODO: create proper assert for Entity existence

        $testItem->deactivate();

        $this->testItemRepository->save($testItem);
    }
}
