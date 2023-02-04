<?php
declare(strict_types=1);

namespace App\Core;

use Symfony\Component\Uid\UuidV6;

class Uuid extends UuidV6
{
    public function __construct(UuidV6|string $uuid = null)
    {
        parent::__construct((string) $uuid);
    }

    public static function create(UuidV6|string $uuid): self
    {
        return new self($uuid);
    }

    public static function new(): self
    {
        return new self(self::string());
    }

    public static function string(): string
    {
        return self::v6()->jsonSerialize();
    }
}
