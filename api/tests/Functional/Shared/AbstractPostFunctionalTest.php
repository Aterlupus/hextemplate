<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPostFunctionalTest extends AbstractHttpFunctionalTest
{
    abstract protected static function getEntityClass(): string;

    abstract protected static function getUri(): string;

    abstract protected static function getEntityJson(): RequestJson;

    protected static function getHttpMethod(): string
    {
        return 'POST';
    }

    protected function itCreates(): void
    {
        $json = static::getEntityJson();

        $response = $this->post(static::getUri(), $json);
        self::assertResponseCode(Response::HTTP_CREATED);

        $id = $response['id'];
        $domainEntity = $this->findEntity(static::getEntityClass(), $id);

        self::assertNotNull($domainEntity);
        self::assertEquals($id, $domainEntity->getId()->getValue());
        foreach ($json->getKeys() as $fieldName) {
            $getter = sprintf('get%s', $fieldName);
            self::assertEquals($json->get($fieldName), $domainEntity->$getter()->getValue());
        }
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
