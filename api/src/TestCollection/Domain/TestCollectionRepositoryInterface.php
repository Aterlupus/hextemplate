<?php
declare(strict_types=1);

namespace App\TestCollection\Domain;

interface TestCollectionRepositoryInterface
{
    public function get(TestCollectionId $testCollectionId): ?TestCollection;

    public function save(TestCollection $testCollection): void;

    public function delete(TestCollection $testCollection): void;
}
