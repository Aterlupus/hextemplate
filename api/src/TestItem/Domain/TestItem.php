<?php
declare(strict_types=1);

namespace App\TestItem\Domain;

use App\Shared\Domain\AbstractDomainEntity;

//TODO: Maybe "Ids" should be part of shared directory?
use App\TestCollection\Domain\TestCollectionId;
use Webmozart\Assert\Assert;

class TestItem extends AbstractDomainEntity
{
    private function __construct(
        protected TestItemId $id,
        protected TestItemDescription $description,
        protected TestItemAmount $amount,
        protected TestItemIsActive $isActive,
        protected TestItemComment $comment,
        protected TestCollectionId $testCollectionId,
    ) {}

    public static function create(
        TestItemId $id,
        TestItemDescription $description,
        TestItemAmount $amount,
        TestItemIsActive $active,
        TestItemComment $comment,
        TestCollectionId $testCollectionId
    ): self {
        return new self(
            $id,
            $description,
            $amount,
            $active,
            $comment,
            $testCollectionId
        );
    }

    public function update(
        TestItemDescription $description,
        TestItemAmount $amount,
        TestCollectionId $testCollectionId,
    ): void {
        $this->description = $description;
        $this->amount = $amount;
        $this->testCollectionId = $testCollectionId;
    }

    public function activate(): void
    {
        Assert::false($this->getIsActive()->getValue(), sprintf('Couldn\'t activate TestItem of id "%s". TestItem already active', $this->getId()->getValue()));
        $this->isActive = new TestItemIsActive(true);
    }

    public function deactivate(): void
    {
        Assert::true($this->getIsActive()->getValue(), sprintf('Couldn\'t deactivate TestItem of id "%s". TestItem already inactive', $this->getId()->getValue()));
        $this->isActive = new TestItemIsActive(false);
    }

    public function updateComment(
        TestItemComment $comment
    ): void {
        $this->comment = $comment;
    }

    public function getId(): TestItemId
    {
        return $this->id;
    }

    public function getDescription(): TestItemDescription
    {
        return $this->description;
    }

    public function getAmount(): TestItemAmount
    {
        return $this->amount;
    }

    public function getIsActive(): TestItemIsActive
    {
        return $this->isActive;
    }

    public function getComment(): TestItemComment
    {
        return $this->comment;
    }

    public function getTestCollectionId(): TestCollectionId
    {
        return $this->testCollectionId;
    }
}
