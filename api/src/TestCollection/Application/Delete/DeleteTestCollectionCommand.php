<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Delete;

use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\CreatableFromArrayInterface;

class DeleteTestCollectionCommand implements CommandInterface, CreatableFromArrayInterface
{
    public function __construct(
        private readonly string $id
    ) {}

    public static function createFromArray(array $data): CreatableFromArrayInterface
    {
        return new self(
            (string) $data['id']
        );
    }

    public function getId(): string
    {
        return $this->id;
    }
}
