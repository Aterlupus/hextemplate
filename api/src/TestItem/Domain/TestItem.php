<?php
declare(strict_types=1);

namespace App\TestItem\Domain;

use App\Shared\Domain\AbstractDomainEntity;

//TODO: Maybe "Ids" should be part of shared directory?
use App\TestCollection\Domain\TestCollectionId;
use Webmozart\Assert\Assert;

class TestItem extends AbstractDomainEntity
{
    public function __construct(
        protected TestItemId          $id,
        protected TestItemDescription $description,
        protected TestItemAmount      $amount,
        protected TestItemIsActive    $isActive,
        protected TestCollectionId    $testCollectionId,
    ) {}

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
        Assert::false($this->getIsActive()->getValue());
        $this->isActive = new TestItemIsActive(true);
    }

    public function deactivate(): void
    {
        Assert::true($this->getIsActive()->getValue());
        $this->isActive = new TestItemIsActive(false);
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

    public function getTestCollectionId(): TestCollectionId
    {
        return $this->testCollectionId;
    }
}
