<?php
declare(strict_types=1);

namespace App\TestItem\Infrastructure\ApiPlatform;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Core\Uuid;
use App\Shared\Infrastructure\ApiPlatform\State\Processor\ApiPlatformDomainEntityProcessor;
use App\Shared\Infrastructure\ApiPlatform\State\Provider\ApiPlatformDomainEntityProvider;
use App\TestItem\Domain\TestItem;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'TestItem',
    operations: [
        new Get(
            provider: ApiPlatformDomainEntityProvider::class,
        ),
        new Post(
            validationContext: ['groups' => ['create']],
            processor: ApiPlatformDomainEntityProcessor::class,
        ),
    ],
    routePrefix: '/api',
)]
final class TestItemResource implements JsonSerializable
{
    public function __construct(
        #[ApiProperty(identifier: true, writable: false)]
        public ?Uuid $id = null,

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\Length(min: 3, max: 1024, groups: ['create', 'Default'])]
        public ?string $description = null,

        #[Assert\NotNull(groups: ['create'])]
        public ?int $amount = null,

        #[Assert\NotNull(groups: ['create'])]
        public ?Uuid $testCollectionId = null,
    ) {}

    public static function fromModel(TestItem $testItem): self
    {
        return new self(
            new Uuid($testItem->getId()->getValue()),
            $testItem->getDescription()->getValue(),
            $testItem->getAmount()->getValue(),
            new Uuid($testItem->getTestCollectionId()->getValue()),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'amount' => $this->amount,
            'testCollectionId' => $this->testCollectionId,
        ];
    }
}