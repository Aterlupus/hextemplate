<?php
declare(strict_types=1);

namespace App\TestItem\Application\UpdateComment;

use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\CreatableFromArrayInterface;

class UpdateCommentTestItemCommand implements CommandInterface, CreatableFromArrayInterface
{
    public function __construct(
        private readonly string $id,
        private readonly ?string $comment
    ) {}

    public static function createFromArray(array $data): self
    {
        return new self(
            (string) $data['id'],
            $data['comment']
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
