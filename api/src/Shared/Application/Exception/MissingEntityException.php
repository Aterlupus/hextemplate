<?php
declare(strict_types=1);

namespace App\Shared\Application\Exception;

use Exception;

class MissingEntityException extends Exception
{
    public function __construct(string $id, string $entityClass)
    {
        parent::__construct(sprintf('Entity %s of id "%s not found"', $entityClass, $id));
    }
}
