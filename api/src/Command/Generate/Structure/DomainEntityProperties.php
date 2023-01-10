<?php
declare(strict_types=1);

namespace App\Command\Generate\Structure;

use App\Core\Util\Set;
use Doctrine\Common\Collections\ArrayCollection;

class DomainEntityProperties extends ArrayCollection
{
    public function getId(): DomainEntityProperty
    {
        return Set::getFirstWhich(
            $this->toArray(),
            fn(DomainEntityProperty $property) => $property->isId()
        );
    }

    public function getAllWithoutId(): iterable
    {
        return array_filter(
            $this->toArray(),
            fn(DomainEntityProperty $property) => false === $property->isId(),
        );
    }

    public function getExternals(): array
    {
        return array_filter(
            $this->toArray(),
            fn(DomainEntityProperty $property) => true === $property->isExternal()
        );
    }

    public function getNames(): array
    {
        return array_map(
            fn(DomainEntityProperty $property) => $property->getName(),
            $this->toArray()
        );
    }
}
