<?php
declare(strict_types=1);

namespace App\TestItem\Application\Update;

use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\CreatableFromArrayInterface;

class UpdateTestItemCommand implements CommandInterface, CreatableFromArrayInterface
{
    public function __construct(
        private readonly string $id,
        private readonly string $description,
        private readonly int $amount,
        private readonly string $testCollectionId
    ) {}

    public static function createFromArray(array $data): self
    {
        return new self(
            (string) $data['id'],
            $data['description'],
            $data['amount'],
            (string) $data['testCollectionId']
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTestCollectionId(): string
    {
        return $this->testCollectionId;
    }
}
