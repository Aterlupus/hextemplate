<?php
declare(strict_types=1);

namespace Test\Functional;

use App\Core\Util\Set;
use App\Shared\Domain\AbstractDomainEntity;
use Symfony\Component\HttpFoundation\Response;
use Test\Helper\Random;

abstract class AbstractPutFunctionalTest extends AbstractFunctionalTest
{
    abstract protected static function getUri(): string;

    abstract protected static function getEntityJson(): RequestJson;

    abstract protected function createEntityAndGetId(): string;

    abstract protected static function assertRequestAndEntityIdentity(
        string $id,
        RequestJson $json,
        AbstractDomainEntity $domainEntity
    ): void;

    protected function itUpdates(string $entityClass): void
    {
        $testItemId = $this->createEntityAndGetId();
        $json = static::getEntityJson();

        $url = sprintf(static::getUri(), $testItemId);
        $response = $this->put($url, $json);
        self::assertResponseCode(Response::HTTP_OK);

        $id = $response['id'];
        $domainEntity = $this->findEntity($entityClass, $id);

        static::assertRequestAndEntityIdentity(
            $id,
            $json,
            $domainEntity
        );
    }

    protected function itFailsOnUpdatingWithoutFieldValue(string $fieldName): void
    {
        $testItemId = $this->createEntityAndGetId();

        $json = static::getEntityJson()
            ->remove($fieldName);

        $url = sprintf(static::getUri(), $testItemId);
        $response = $this->put($url, $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        self::assertEquals('This value should not be null.', $violation['message']);
    }

    //TODO: Consider making call for "$minLength + 1" Random::getString to check that a little longer string works
    protected function itFailsOnUpdatingWithTooShortFieldValue(string $fieldName, int $minLength): void
    {
        $testItemId = $this->createEntityAndGetId();

        $json = static::getEntityJson()
            ->set($fieldName, '');

        $url = sprintf(static::getUri(), $testItemId);
        $response = $this->put($url, $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        $assertedMessage = sprintf(
            'This value is too short. It should have %d character%s or more.',
            $minLength,
            $minLength > 1 ? 's' : ''
        );
        self::assertEquals($assertedMessage, $violation['message']);
    }

    //TODO: Consider making call for "$minLength - 1" Random::getString to check that a little shorter string works
    protected function itFailsOnUpdatingWithTooLongFieldValue(string $fieldName, int $maxLength): void
    {
        $testItemId = $this->createEntityAndGetId();

        $json = static::getEntityJson()
            ->set($fieldName, Random::getString($maxLength + 1));

        $url = sprintf(static::getUri(), $testItemId);
        $response = $this->put($url, $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        $assertedMessage = sprintf(
            'This value is too long. It should have %d characters or less.',
            $maxLength
        );
        self::assertEquals($assertedMessage, $violation['message']);
    }

    //TODO: Borrowed
    private static function getOnlyViolation(array $response): array
    {
        self::assertCount(1, $response['violations']);
        return Set::getOnly($response['violations']);
    }
}
