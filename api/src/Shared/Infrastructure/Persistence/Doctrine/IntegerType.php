<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Infrastructure\Bundles\DoctrineCustomTypesRegisterer\DoctrineCustomTypeInterface;
use Doctrine\DBAL\Types\IntegerType as DoctrineIntegerType;

abstract class IntegerType extends DoctrineIntegerType implements DoctrineCustomTypeInterface
{

}
