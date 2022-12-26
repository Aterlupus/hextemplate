<?php
declare(strict_types=1);

namespace App\TestItem\Domain;

interface TestItemRepositoryInterface
{
    public function get(TestItemId $testItemId): ?TestItem;

    public function save(TestItem $testItem): void;

    public function delete(TestItem $testItem): void;
}
