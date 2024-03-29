<?php
declare(strict_types=1);

namespace App\TestCollection\Application\Create;

use App\Core\Uuid;
use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\CreatableFromArrayInterface;

class CreateTestCollectionCommand implements CommandInterface, CreatableFromArrayInterface
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
    ) {}

    public static function createFromArray(array $data): self
    {
        return new self(
            Uuid::string(),
            $data['name']
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
