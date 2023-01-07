<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Delete;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\Shared\Application\Exception\MissingEntityException;
use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionRepositoryInterface;

class DeleteTestCollectionCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestCollectionRepositoryInterface $testCollectionRepository
    ) {}

    /**
     * @throws MissingEntityException
     */
    public function __invoke(DeleteTestCollectionCommand $command): void
    {
        $testCollection = $this->testCollectionRepository->get(new TestCollectionId($command->getId()));
        self::assertEntityNotNull($command->getId(), TestCollection::class, $testCollection);

        $this->testCollectionRepository->delete($testCollection);
    }
}
