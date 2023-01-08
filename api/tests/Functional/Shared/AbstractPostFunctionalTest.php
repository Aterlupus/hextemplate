<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPostFunctionalTest extends AbstractHttpFunctionalTest
{
    abstract protected static function getEntityClass(): string;

    abstract protected static function getUri(): string;

    abstract protected function getEntityJson(): RequestJson;

    protected static function getHttpMethod(): string
    {
        return 'POST';
    }

    protected function itCreates(?RequestJson $jsonRequest = null): void
    {
        $jsonRequest = $jsonRequest ?? $this->getEntityJson();

        $response = $this->post(static::getUri(), $jsonRequest);
        self::assertResponseCode(Response::HTTP_CREATED);

        $id = $response['id'];
        $domainEntity = $this->findEntity(static::getEntityClass(), $id);

        static::assertRequestAndEntityIdentity(
            $id,
            $jsonRequest,
            $domainEntity
        );
    }

    protected function itFailsOnCreatingWithoutFieldValue(string $fieldName): void
    {
        $this->itFailsOnActingWithoutFieldValue(
            static::getUri(),
            $fieldName
        );
    }

    protected function itFailsOnCreatingWithTooShortFieldValue(string $fieldName, int $minLength): void
    {
        $this->itFailsOnActingWithTooShortFieldValue(
            static::getUri(),
            $fieldName,
            $minLength
        );
    }

    protected function itFailsOnCreatingWithTooLongFieldValue(string $fieldName, int $maxLength): void
    {
        $this->itFailsOnActingWithTooLongFieldValue(
            static::getUri(),
            $fieldName,
            $maxLength
        );
    }
}
