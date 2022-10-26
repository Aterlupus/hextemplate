<?php
declare(strict_types=1);

namespace App\Core\Exception\InvalidSuitKey;

use App\Core\Exception\InvalidSuitKeyException;

class InvalidMonthException extends InvalidSuitKeyException
{
    protected function getExceptionMessage(): string
    {
        return sprintf('Invalid month "%s"', $this->getSuitKey());
    }
}
