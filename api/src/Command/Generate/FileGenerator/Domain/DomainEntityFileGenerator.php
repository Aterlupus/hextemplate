<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Domain;

use App\Command\Generate\FileGenerator\AbstractFileGenerator;
use App\Command\Generate\Structure\DomainEntityProperties;
use App\Command\Generate\Structure\DomainEntityProperty;
use App\Shared\Domain\AbstractDomainEntity;
use Nette\PhpGenerator\Method;

class DomainEntityFileGenerator extends AbstractFileGenerator
{
    public function __construct(
        string $domain,
        private readonly DomainEntityProperties $properties
    ) {
        parent::__construct($domain);
    }

    public function populatePhpFile(): void
    {
        $this->setFinal();
        $this->addExtends(AbstractDomainEntity::class);
        $this->addUse(AbstractDomainEntity::class);

        $this->addConstructor();

        foreach ($this->properties as $property) {
            $this->addPropertyGetter($property);
        }
    }

    private function addConstructor(): void
    {
        $constructor = $this->getFile()->addConstructor();

        /** @var DomainEntityProperty $property */
        foreach ($this->properties as $property) {
            $constructor
                ->addPromotedParameter($property->getName())
                ->setProtected()
                ->setType(self::getPropertyName($this->getDomain(), $property->getName()));
        }
    }

    private function addPropertyGetter(DomainEntityProperty $property): void
    {
        $domainPropertyType = self::getPropertyName($this->getDomain(), $property->getName());

        $getter = new Method(sprintf('get%s', ucfirst($property->getName())));
        $getter
            ->setPublic()
            ->setReturnType($domainPropertyType)
            ->setBody(sprintf('return $this->%s;', $property->getName()));

        $this->getFile()->getPhpClass()->addMember($getter);
    }

    private static function getPropertyName(string $domain, string $propertyName): string
    {
        return sprintf('%s%s', $domain, ucfirst($propertyName));
    }

    protected function getClassname(): string
    {
        return $this->getDomain();
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Domain', $this->getDomain());
    }
}
