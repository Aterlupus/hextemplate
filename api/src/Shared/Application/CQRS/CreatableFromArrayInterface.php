<?php
declare(strict_types=1);

namespace App\Shared\Application\CQRS;

interface CreatableFromArrayInterface
{
    public static function createFromArray(array $data): self;
}
