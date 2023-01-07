<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Update;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\Shared\Application\Exception\MissingEntityException;
use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionName;
use App\TestCollection\Domain\TestCollectionRepositoryInterface;

class UpdateTestCollectionCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestCollectionRepositoryInterface $testCollectionRepository
    ) {}

    /**
     * @throws MissingEntityException
     */
    public function __invoke(UpdateTestCollectionCommand $command): void
    {
        $testCollection = $this->testCollectionRepository->get(new TestCollectionId($command->getId()));
        self::assertEntityNotNull($command->getId(), TestCollection::class, $testCollection);

        $testCollection->update(
            new TestCollectionName($command->getName())
        );

        $this->testCollectionRepository->save($testCollection);
    }
}
