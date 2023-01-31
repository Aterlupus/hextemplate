<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemAmount;
use App\TestItem\Domain\TestItemComment;
use App\TestItem\Domain\TestItemDescription;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemIsActive;
use Test\Functional\TestCollection\TestCollectionCreatorTrait;
use Test\Helper\Random;

trait TestItemCreatorTrait
{
    use TestCollectionCreatorTrait;


    public function getTestItem(array $values = []): TestItem
    {
        $testItem = TestItem::create(
            $values['id'] ?? TestItemId::new(),
            new TestItemDescription(Random::getString(200)),
            new TestItemAmount(Random::getPositiveInteger(500)),
            $values['isActive'] ?? new TestItemIsActive(true),
            Random::chance() ? new TestItemComment(Random::getString(1000)) : new TestItemComment(null),
            isset($values['testCollection']) ? $values['testCollection']->getId() : $this->getTestCollection()->getId(),
        );

        $this->eg->saveEntity($testItem);

        return $testItem;
    }
}
