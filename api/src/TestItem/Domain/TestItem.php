<?php
declare(strict_types=1);

namespace App\TestItem\Domain;

use App\Shared\Domain\AbstractDomainEntity;
use App\TestCollection\Domain\TestCollectionId; //TODO: Maybe "Ids" should be part of shared directory?

class TestItem extends AbstractDomainEntity
{
    public function __construct(
        protected readonly TestItemId          $id,
        protected readonly TestItemDescription $description,
        protected readonly TestItemAmount      $amount,
        protected readonly TestCollectionId    $testCollectionId,
    ) {}

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

    public function getTestCollectionId(): TestCollectionId
    {
        return $this->testCollectionId;
    }
}
