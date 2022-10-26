<?php
declare(strict_types=1);

namespace App\TestCollection\Domain;

use App\Shared\Domain\AbstractDomainEntity;

class TestCollection extends AbstractDomainEntity
{
    public function __construct(
        protected readonly TestCollectionId           $id,
        protected readonly TestCollectionName         $name,
        protected readonly TestCollectionTestItemsIds $testItemsIds
    ) {
        //TODO: Assert $items to all be of class TestItemId?
    }

    public function getId(): TestCollectionId
    {
        return $this->id;
    }

    public function getName(): TestCollectionName
    {
        return $this->name;
    }

    public function getTestItemsIds(): TestCollectionTestItemsIds
    {
        return $this->testItemsIds;
    }
}
