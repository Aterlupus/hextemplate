<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Update;

use App\Core\Util\StackTraceHelper;
use App\Shared\Application\CQRS\CommandHandlerInterface;
use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionName;
use App\TestCollection\Domain\TestCollectionRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateTestCollectionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TestCollectionRepositoryInterface $testCollectionRepository
    ) {}

    public function __invoke(UpdateTestCollectionCommand $command): void
    {
        $testCollection = $this->testCollectionRepository->get(new TestCollectionId($command->getId()));
        Assert::notNull($testCollection, sprintf("Entity %s of id %s not found. %s", TestCollection::class, $command->getId(), StackTraceHelper::getStackTraceMessage()));

        $testCollection->update(
            new TestCollectionName($command->getName())
        );

        $this->testCollectionRepository->save($testCollection);
    }
}
