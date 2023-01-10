<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Infrastructure\ApiPlatform;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Command\Generate\Structure\DomainEntityProperties;
use App\Command\Generate\Structure\DomainEntityProperty;
use App\Command\Generate\FileGenerator\AbstractFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainEntityFileGenerator;
use App\Core\Uuid;
use App\Shared\Infrastructure\ApiPlatform\State\Processor\ApiPlatformDomainEntityProcessor;
use App\Shared\Infrastructure\ApiPlatform\State\Provider\ApiPlatformDomainEntityProvider;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

class DomainEntityResourceFileGenerator extends AbstractFileGenerator
{
    private const REPLACEMENT_TAG = 'replacement_tag';

    private const ATTRIBUTE_OPERATIONS = <<<'EOD'
    
            new Get(
                provider: ApiPlatformDomainEntityProvider::class,
            ),
            new Post(
                processor: ApiPlatformDomainEntityProcessor::class,
            ),
            //TODO: Implement Delete action
            //new Delete(
            //    provider: ApiPlatformDomainEntityProvider::class,
            //    processor: ApiPlatformDomainEntityProcessor::class,
            //)
        
    EOD;


    public function __construct(
        string $domain,
        private readonly DomainEntityProperties $properties
    ) {
        parent::__construct($domain);
    }

    protected function populatePhpFile(): void
    {
        $this->setFinal();
        $this->addImplements(JsonSerializable::class);

        $this->addUse(Uuid::class);
        $this->addUse(JsonSerializable::class);
        $this->addUse(ApiProperty::class);
        $this->addUse(ApiResource::class);
        $this->addUse(Assert::class, 'Assert');
        $this->addUse(ApiPlatformDomainEntityProvider::class);
        $this->addUse(ApiPlatformDomainEntityProcessor::class);
        $this->addUse(Get::class);
        $this->addUse(Post::class);
        $this->addUse(Delete::class);

        $this->addApiResourceAttribute();

        $this->addConstructor();
        $this->addFromModelMethod();
        $this->addJsonSerializeMethod();

        $this->addFinalReplacement(sprintf("'%s'", self::REPLACEMENT_TAG), self::ATTRIBUTE_OPERATIONS);
    }

    private function addApiResourceAttribute(): void
    {
        $this->getFile()->getPhpClass()->addAttribute('ApiResource', [
            'shortName' => $this->getDomain(),
            'operations' => [
                self::REPLACEMENT_TAG
            ],
            'routePrefix' => '/api',
        ]);
    }

    private function addConstructor(): void
    {
        $constructor = $this->getFile()->addConstructor();

        /** @var DomainEntityProperty $property */
        foreach ($this->properties as $property) {
            $promotedParameter = $constructor->addPromotedParameter(
                $property->getName(),
                null,
            );

            if ($property->isId()) {
                $promotedParameter->setType(Uuid::class);
            } else {
                $promotedParameter->setType($property->getType());

            }

            $promotedParameter->setNullable();

            //Attributes
            //ApiProperty
            if ($property->isId()) {
                $promotedParameter->addAttribute('ApiProperty', ['identifier' => true, 'writable' => false]);
            }

            //NotNull
            if (false === $property->isId() && false === $property->isNullable()) {
                $promotedParameter->addAttribute('Assert\NotNull');
            }

            //Length
            if (null !== $property->getMinLength() && null === $property->getMaxLength()) {
                $promotedParameter->addAttribute('Assert\Length', ['min' => $property->getMinLength()]);
            } else if (null === $property->getMinLength() && null !== $property->getMaxLength()) {
                $promotedParameter->addAttribute('Assert\Length', ['max' => $property->getMaxLength()]);
            } else if (null !== $property->getMinLength() || null !== $property->getMaxLength()) {
                $promotedParameter->addAttribute('Assert\Length', ['min' => $property->getMinLength(), 'max' => $property->getMaxLength()]);
            }
        }
    }

    private function addFromModelMethod(): void
    {
        $fromModelBodyFormat = <<<'EOD'
        return new self(
        %s);
        EOD;

        $fromModelRowFormat = <<<'EOD'
            $%s->get%s()->getValue(),
        EOD;

        $uuidRowFormat = <<<'EOD'
            new Uuid($%s->get%s()->getValue()),
        EOD;

        $fromModelRows = '';
        /** @var DomainEntityProperty $property */
        foreach ($this->properties as $property) {
            if ($property->isId()) {
                $fromModelRows .= sprintf($uuidRowFormat, lcfirst($this->getDomain()), ucfirst($property->getName())) . "\n";
            } else {
                $fromModelRows .= sprintf($fromModelRowFormat, lcfirst($this->getDomain()), ucfirst($property->getName())) . "\n";
            }
        }

        $this->getFile()->addMethod(
            'fromModel',
            'public',
            'self',
            parameters: [[
                'name' => lcfirst($this->getDomain()),
                'type' => $this->getDomain(),
            ]],
            isStatic: true,
            body: sprintf($fromModelBodyFormat, $fromModelRows),
        );
    }

    private function addJsonSerializeMethod(): void
    {
        $jsonSerializeBody = <<<'EOD'
        return [
        %s];
        EOD;

        $jsonSerializeRow = <<<'EOD'
            '%s' => $this->%s,
        EOD;

        $jsonSerializeRows = '';
        /** @var DomainEntityProperty $property */
        foreach ($this->properties as $property) {
            $jsonSerializeRows .= sprintf($jsonSerializeRow, $property->getName(), $property->getName()) . "\n";
        }

        $this->getFile()->addMethod(
            'jsonSerialize',
            'public',
            'array',
            body: sprintf($jsonSerializeBody, $jsonSerializeRows),
        );
    }

    public function addDomainEntityUse(DomainEntityFileGenerator $domainEntityGeneratedFile): void
    {
        $this->addUse($domainEntityGeneratedFile->getFullClassname());
    }

    protected function getClassname(): string
    {
        return sprintf('%sResource', $this->getDomain());
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Infrastructure\ApiPlatform', $this->getDomain());
    }
}
