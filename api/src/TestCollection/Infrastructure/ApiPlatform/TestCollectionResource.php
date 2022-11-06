<?php
declare(strict_types=1);

namespace App\TestCollection\Infrastructure\ApiPlatform;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Core\Uuid;
use App\Shared\Infrastructure\ApiPlatform\State\Processor\ApiPlatformDomainEntityProcessor;
use App\Shared\Infrastructure\ApiPlatform\State\Provider\ApiPlatformDomainEntityProvider;
use App\TestCollection\Domain\TestCollection;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'TestCollection',
    operations: [
        new Get(
            provider: ApiPlatformDomainEntityProvider::class,
        ),
        new Post(
            processor: ApiPlatformDomainEntityProcessor::class,
        ),
        new Put(
            provider: ApiPlatformDomainEntityProvider::class,
            processor: ApiPlatformDomainEntityProcessor::class,
        ),
        new Delete(
            provider: ApiPlatformDomainEntityProvider::class,
            processor: ApiPlatformDomainEntityProcessor::class,
        )
    ],
    routePrefix: '/api',
)]
final class TestCollectionResource implements JsonSerializable
{
    public function __construct(
        #[ApiProperty(identifier: true, writable: false)]
        public ?Uuid $id = null,

        #[Assert\NotNull]
        #[Assert\Length(min: 3, max: 255)]
        public ?string $name = null,

        #[Assert\NotNull]
        public array $testItemsIds = []
    ) {}

    public static function fromModel(TestCollection $testCollection): self
    {
        return new self(
            new Uuid($testCollection->getId()->getValue()),
            $testCollection->getName()->getValue(),
            $testCollection->getTestItemsIds()->getValues(),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'testItemsIds' => $this->testItemsIds,
        ];
    }
}
