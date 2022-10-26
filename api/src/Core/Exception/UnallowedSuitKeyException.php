<?php
declare(strict_types=1);

namespace App\Core\Exception;

use Exception;

class UnallowedSuitKeyException extends Exception
{
    public function __construct(
        private readonly string $suitClass,
        private readonly string $suitKey
    ) {
        parent::__construct($this->getExceptionMessage());
    }

    protected function getExceptionMessage(): string
    {
        return sprintf('Unallowed key "%s" for Suit of class %s', $this->getSuitKey(), $this->getSuitClass());
    }

    public function getSuitClass(): string
    {
        return $this->suitClass;
    }

    public function getSuitKey(): string
    {
        return $this->suitKey;
    }
}
