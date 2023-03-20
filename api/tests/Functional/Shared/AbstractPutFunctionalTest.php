<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use App\Core\Uuid;
use App\Shared\Domain\AbstractDomainEntity;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPutFunctionalTest extends AbstractHttpFunctionalTest
{
    abstract protected static function getEntityClass(): string;

    abstract protected static function getUri(): string;

    abstract protected function getEntityJson(): RequestJson;

    abstract protected function createEntity(): AbstractDomainEntity;

    protected static function getHttpMethod(): string
    {
        return 'PUT';
    }

    protected function itUpdates(?RequestJson $jsonRequest = null): void
    {
        $entityId = $this->createEntity()->getId();
        $jsonRequest = $jsonRequest ?? $this->getEntityJson();

        $uri = sprintf(static::getUri(), $entityId);
        $response = $this->put($uri, $jsonRequest);
        self::assertResponseCode(Response::HTTP_OK);

        $id = $response['id'];
        $domainEntity = $this->findEntity(static::getEntityClass(), $id);

        static::assertRequestAndEntityIdentity(
            $id,
            $jsonRequest,
            $domainEntity
        );
    }

    protected function itFailsOnUpdatingNonExistent(): void
    {
        $json = $this->getEntityJson();
        $nonExistentId = Uuid::new();

        $uri = sprintf(static::getUri(), (string) $nonExistentId);
        $response = $this->executeRequest(static::getHttpMethod(), $uri, $json);
        self::assertResponseCode(Response::HTTP_NOT_FOUND);
        self::assertEquals(
            sprintf('Entity %s of id "%s" not found', static::getEntityClass(), $nonExistentId),
            $response
        );
    }

    protected function itFailsOnUpdatingWithoutFieldValue(string $fieldName): void
    {
        $entityId = $this->createEntity()->getId();

        $this->itFailsOnActingWithoutFieldValue(
            sprintf(static::getUri(), $entityId),
            $fieldName
        );
    }

    protected function itFailsOnUpdatingWithTooShortFieldValue(string $fieldName, int $minLength): void
    {
        $entityId = $this->createEntity()->getId();

        $this->itFailsOnActingWithTooShortFieldValue(
            sprintf(static::getUri(), $entityId),
            $fieldName,
            $minLength
        );
    }

    protected function itFailsOnUpdatingWithTooLongFieldValue(string $fieldName, int $maxLength): void
    {
        $entityId = $this->createEntity()->getId();

        $this->itFailsOnActingWithTooLongFieldValue(
            sprintf(static::getUri(), $entityId),
            $fieldName,
            $maxLength
        );
    }
}
