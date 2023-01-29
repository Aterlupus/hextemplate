<?php
declare(strict_types=1);

namespace App\TestItem\Infrastructure\ApiPlatform;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
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
        new GetCollection(
            provider: ApiPlatformDomainEntityProvider::class,
        ),
        new Post(
            validationContext: ['groups' => ['create']],
            processor: ApiPlatformDomainEntityProcessor::class,
        ),
        new Put(
            validationContext: ['groups' => ['update']],
            name: 'update',
            provider: ApiPlatformDomainEntityProvider::class,
            processor: ApiPlatformDomainEntityProcessor::class,
        ),
        new Put(
            '/test_items/{id}/update-comment.{_format}',
            validationContext: ['groups' => ['updateComment']],
            name: 'updateComment',
            provider: ApiPlatformDomainEntityProvider::class,
            processor: ApiPlatformDomainEntityProcessor::class,
        ),
        new Patch(
            '/test_items/{id}/activate.{_format}',
            name: 'activate',
            provider: ApiPlatformDomainEntityProvider::class,
            processor: ApiPlatformDomainEntityProcessor::class,
        ),
        new Patch(
            '/test_items/{id}/deactivate.{_format}',
            name: 'deactivate',
            provider: ApiPlatformDomainEntityProvider::class,
            processor: ApiPlatformDomainEntityProcessor::class,
        ),
        new Delete(
            provider: ApiPlatformDomainEntityProvider::class,
            processor: ApiPlatformDomainEntityProcessor::class,
        ),
    ],
    routePrefix: '/api',
    normalizationContext: [
          "skip_null_values" => false,
    ],
)]
final class TestItemResource implements JsonSerializable
{
    public function __construct(
        #[ApiProperty(identifier: true, writable: false)]
        public ?Uuid $id = null,

        #[Assert\NotNull(groups: ['create', 'update'])]
        #[Assert\Length(min: 3, max: 1024, groups: ['create', 'update'])]
        public ?string $description = null,

        #[Assert\NotNull(groups: ['create', 'update'])]
        public ?int $amount = null,

        //TODO: maybe isActive default value should be determined by Application/Domain layer?
        #[Assert\NotNull]
        public ?bool $isActive = true,

        #[Assert\Length(min: 16, groups: ['create', 'updateComment'])]
        public ?string $comment = null,

        #[Assert\NotNull(groups: ['create', 'update'])]
        public ?Uuid $testCollectionId = null,
    ) {}

    public static function fromModel(TestItem $testItem): self
    {
        return new self(
            new Uuid($testItem->getId()->getValue()),
            $testItem->getDescription()->getValue(),
            $testItem->getAmount()->getValue(),
            $testItem->getIsActive()->getValue(),
            $testItem->getComment()->getValue(),
            new Uuid($testItem->getTestCollectionId()->getValue()),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'amount' => $this->amount,
            'isActive' => $this->isActive,
            'comment' => $this->comment,
            'testCollectionId' => $this->testCollectionId,
        ];
    }
}
