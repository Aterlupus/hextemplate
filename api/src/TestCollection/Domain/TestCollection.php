<?php
declare(strict_types=1);

namespace App\TestCollection\Domain;

use App\Shared\Domain\AbstractDomainEntity;

class TestCollection extends AbstractDomainEntity
{
    private function __construct(
        protected TestCollectionId $id,
        protected TestCollectionName $name,
        protected TestCollectionTestItemsIds $testItemsIds
    ) {
        //TODO: Assert $items to all be of class TestItemId?
    }

    public static function create(
        TestCollectionId $id,
        TestCollectionName $name,
        TestCollectionTestItemsIds $testItemsIds
    ): self {
        return new self(
            $id,
            $name,
            $testItemsIds
        );
    }

    public function update(
        TestCollectionName $name,
        TestCollectionTestItemsIds $testItemsIds
    ): void {
        $this->name = $name;
        $this->testItemsIds = $testItemsIds;
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
