<?php
declare(strict_types=1);

namespace App\TestItem\Application\UpdateComment;

use App\Shared\Application\CQRS\AbstractCommandHandler;
use App\Shared\Application\Exception\MissingEntityException;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemComment;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class UpdateCommentTestItemCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly TestItemRepositoryInterface $testItemRepository
    ) {}

    /**
     * @throws MissingEntityException
     */
    public function __invoke(UpdateCommentTestItemCommand $command): void
    {
        $testItem = $this->testItemRepository->get(new TestItemId($command->getId()));
        self::assertEntityNotNull($command->getId(),TestItem::class, $testItem);

        $testItem->updateComment(
            new TestItemComment($command->getComment()),
        );

        $this->testItemRepository->save($testItem);
    }
}
