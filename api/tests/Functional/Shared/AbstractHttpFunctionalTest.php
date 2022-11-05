<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use Symfony\Component\HttpFoundation\Response;
use Test\Helper\Random;

//TODO: Rework all test framework into aggregation based instead of inheritance based
abstract class AbstractHttpFunctionalTest extends AbstractFunctionalTest
{
    abstract protected static function getHttpMethod(): string;

    abstract protected static function getEntityJson(): RequestJson;

    protected function itFailsOnActingWithoutFieldValue(string $uri, string $fieldName)
    {
        $json = static::getEntityJson()
            ->remove($fieldName);

        $response = $this->executeRequest(static::getHttpMethod(), $uri, $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        self::assertEquals(ViolationMessageHelper::shouldNotBeNull(), $violation['message']);
    }

    //TODO: Consider making call for "$minLength + 1" Random::getString to check that a little longer string works
    protected function itFailsOnActingWithTooShortFieldValue(string $uri, string $fieldName, int $minLength)
    {
        $json = static::getEntityJson()
            ->set($fieldName, '');

        $response = $this->executeRequest(static::getHttpMethod(), $uri, $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        self::assertEquals(ViolationMessageHelper::isTooShort($minLength), $violation['message']);
    }

    //TODO: Consider making call for "$minLength - 1" Random::getString to check that a little shorter string works
    protected function itFailsOnActingWithTooLongFieldValue(string $uri, string $fieldName, int $maxLength): void
    {
        $json = static::getEntityJson()
            ->set($fieldName, Random::getString($maxLength + 1));

        $response = $this->executeRequest(static::getHttpMethod(), $uri, $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        self::assertEquals(ViolationMessageHelper::isTooLong($maxLength), $violation['message']);
    }
}
