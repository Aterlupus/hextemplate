<?php
declare(strict_types=1);

namespace App\TestItem\Application\Activate;

use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class ActivateTestItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    public function __invoke(ActivateTestItemCommand $command): void
    {
        $testItem = $this->testItemRepository->get(new TestItemId($command->getId()));
        //TODO: create proper assert for Entity existence

        $testItem->activate();

        $this->testItemRepository->save($testItem);
    }
}
