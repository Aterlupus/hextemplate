<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use App\Shared\Domain\AbstractDomainEntity;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPutFunctionalTest extends AbstractHttpFunctionalTest
{
    abstract protected static function getEntityClass(): string;

    abstract protected static function getUri(): string;

    abstract protected static function getEntityJson(): RequestJson;

    abstract protected function createEntity(): AbstractDomainEntity;

    protected static function getHttpMethod(): string
    {
        return 'PUT';
    }

    protected static function assertRequestAndEntityIdentity(
        string $id,
        RequestJson $json,
        AbstractDomainEntity $domainEntity
    ): void {
        self::assertNotNull($domainEntity);
        self::assertEquals($id, $domainEntity->getId()->getValue());

        $jsonKeys = $json->getKeys();
        $entityKeys = array_keys($domainEntity->jsonSerialize());
        $remainingKeys = array_diff($entityKeys, $jsonKeys, ['id']);

        foreach ($jsonKeys as $fieldName) {
            $getter = sprintf('get%s', $fieldName);
            self::assertEquals($json->get($fieldName), $domainEntity->$getter()->getValue());
        }

        foreach ($remainingKeys as $fieldName) {
            $getter = sprintf('get%s', $fieldName);
            self::assertEmpty($domainEntity->$getter()->getValue());
        }
    }

    protected function itUpdates(): void
    {
        $entityId = $this->createEntity()->getId();
        $json = static::getEntityJson();

        $url = sprintf(static::getUri(), $entityId);
        $response = $this->put($url, $json);
        self::assertResponseCode(Response::HTTP_OK);

        $id = $response['id'];
        $domainEntity = $this->findEntity(static::getEntityClass(), $id);

        static::assertRequestAndEntityIdentity(
            $id,
            $json,
            $domainEntity
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
