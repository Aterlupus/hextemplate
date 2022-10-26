<?php
declare(strict_types=1);

namespace App\Core;

use Symfony\Component\Uid\UuidV6;

class Uuid extends UuidV6
{
    public static function new(): self
    {
        return new self(self::string());
    }

    public static function string(): string
    {
        return self::v6()->jsonSerialize();
    }
}
