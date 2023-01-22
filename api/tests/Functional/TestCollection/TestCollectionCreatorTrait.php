<?php
declare(strict_types=1);

namespace Test\Functional\TestCollection;

use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionName;
use App\TestCollection\Domain\TestCollectionTestItemsIds;
use Test\Helper\Random;

trait TestCollectionCreatorTrait
{
    public function getTestCollection(array $values = []): TestCollection
    {
        $testCollection = new TestCollection(
            TestCollectionId::new(),
            new TestCollectionName(Random::getString(16)),
            $values['testItemsIds'] ?? new TestCollectionTestItemsIds([]),
        );

        $this->eg->saveEntity($testCollection);

        return $testCollection;
    }
}
