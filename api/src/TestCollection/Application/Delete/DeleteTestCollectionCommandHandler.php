<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Delete;

use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionRepositoryInterface;

class DeleteTestCollectionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TestCollectionRepositoryInterface $testCollectionRepository
    ) {}

    public function __invoke(DeleteTestCollectionCommand $command): void
    {
        //TODO: create proper assert for Entity existence
        $testCollection = $this->testCollectionRepository->get(new TestCollectionId($command->getId()));

        $this->testCollectionRepository->delete($testCollection);
    }
}
