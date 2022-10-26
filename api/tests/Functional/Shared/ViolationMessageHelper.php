<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

class ViolationMessageHelper
{
    public static function shouldNotBeNull(): string
    {
        return 'This value should not be null.';
    }

    public static function isTooShort(int $minLength): string
    {
        return sprintf(
            'This value is too short. It should have %d character%s or more.',
            $minLength,
            $minLength > 1 ? 's' : ''
        );
    }

    public static function isTooLong(int $maxLength): string
    {
        return sprintf(
            'This value is too long. It should have %d characters or less.',
            $maxLength
        );
    }
}
