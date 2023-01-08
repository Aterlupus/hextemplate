<?php
declare(strict_types=1);

namespace App\TestItem\Application\Create;

use App\Core\Uuid;
use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\CreatableFromArrayInterface;

class CreateTestItemCommand implements CommandInterface, CreatableFromArrayInterface
{
    public function __construct(
        private readonly string $id,
        private readonly string $description,
        private readonly int $amount,
        private readonly bool $isActive,
        private readonly ?string $comment,
        private readonly string $testCollectionId
    ) {}

    public static function createFromArray(array $data): self
    {
        return new self(
            Uuid::string(),
            $data['description'],
            $data['amount'],
            $data['isActive'],
            $data['comment'],
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

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getTestCollectionId(): string
    {
        return $this->testCollectionId;
    }
}
