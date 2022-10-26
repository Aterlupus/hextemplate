<?php
declare(strict_types=1);

namespace Test\Functional;

use App\Core\Util\Set;
use Symfony\Component\HttpFoundation\Response;
use Test\Helper\Random;

abstract class AbstractPostFunctionalTest extends AbstractFunctionalTest
{
    abstract protected static function getUri(): string;

    abstract protected static function getPostJson(): RequestJson;

    protected function itCreates(
        string $entityClass
    ): void {
        $json = static::getPostJson();

        $response = $this->post(static::getUri(), $json);
        self::assertResponseCode(Response::HTTP_CREATED);

        $id = $response['id'];
        $domainEntity = $this->findEntity($entityClass, $id);

        self::assertNotNull($domainEntity);
        self::assertEquals($id, $domainEntity->getId()->getValue());
        foreach ($json->getKeys() as $fieldName) {
            $getter = sprintf('get%s', $fieldName);
            self::assertEquals($json->get($fieldName), $domainEntity->$getter()->getValue());
        }
    }

    protected function itFailsOnCreatingWithoutFieldValue(string $fieldName): void
    {
        $json = static::getPostJson()
            ->remove($fieldName);

        $response = $this->post(static::getUri(), $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        self::assertEquals('This value should not be null.', $violation['message']);
    }

    //TODO: Consider making call for "$minLength + 1" Random::getString to check that a little longer string works
    protected function itFailsOnCreatingWithTooShortFieldValue(
        string $fieldName,
        int $minLength
    ): void {
        $json = static::getPostJson()
            ->set($fieldName, '');

        $response = $this->post(static::getUri(), $json);
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
    protected function itFailsOnCreatingWithTooLongFieldValue(
        string $fieldName,
        int $maxLength
    ): void {
        $json = static::getPostJson()
            ->set($fieldName, Random::getString($maxLength + 1));

        $response = $this->post(static::getUri(), $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        $assertedMessage = sprintf(
            'This value is too long. It should have %d characters or less.',
            $maxLength
        );
        self::assertEquals($assertedMessage, $violation['message']);
    }

    private static function getOnlyViolation(array $response)
    {
        self::assertCount(1, $response['violations']);
        return Set::getOnly($response['violations']);
    }
}
