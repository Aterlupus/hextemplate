<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use App\Shared\Domain\AbstractDomainEntity;
use Symfony\Component\HttpFoundation\Response;
use Test\Helper\Random;

//TODO: Rework all test framework into aggregation based instead of inheritance based
abstract class AbstractHttpFunctionalTest extends AbstractFunctionalTest
{
    abstract protected static function getHttpMethod(): string;

    abstract protected function getEntityJson(): RequestJson;

    protected static function getDefaultFieldsValues(): array
    {
        return [];
    }

    protected function itFailsOnActingWithoutFieldValue(string $uri, string $fieldName)
    {
        $json = $this->getEntityJson()
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
        $json = $this->getEntityJson()
            ->set($fieldName, Random::getString($minLength - 1));

        $response = $this->executeRequest(static::getHttpMethod(), $uri, $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        self::assertEquals(ViolationMessageHelper::isTooShort($minLength), $violation['message']);
    }

    //TODO: Consider making call for "$minLength - 1" Random::getString to check that a little shorter string works
    protected function itFailsOnActingWithTooLongFieldValue(string $uri, string $fieldName, int $maxLength): void
    {
        $json = $this->getEntityJson()
            ->set($fieldName, Random::getString($maxLength + 1));

        $response = $this->executeRequest(static::getHttpMethod(), $uri, $json);
        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        $violation = self::getOnlyViolation($response);
        self::assertEquals($fieldName, $violation['propertyPath']);
        self::assertEquals(ViolationMessageHelper::isTooLong($maxLength), $violation['message']);
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
        $defaultedKeys = array_keys(static::getDefaultFieldsValues());
        $remainingKeys = array_diff($entityKeys, $jsonKeys, $defaultedKeys, ['id']);

        foreach ($jsonKeys as $fieldName) {
            $getter = sprintf('get%s', $fieldName);
            self::assertEquals($json->get($fieldName), $domainEntity->$getter()->getValue());
        }

        foreach ($defaultedKeys as $fieldName) {
            $getter = sprintf('get%s', ucfirst($fieldName));
            self::assertEquals(static::getDefaultFieldsValues()[$fieldName], $domainEntity->$getter()->getValue());
        }

        //TODO
//        foreach ($remainingKeys as $fieldName) {
//            $getter = sprintf('get%s', $fieldName);
//            self::assertEmpty($domainEntity->$getter()->getValue());
//        }
    }

    protected static function assertResponseAndEntityIdentity(
        array $response,
        AbstractDomainEntity $domainEntity
    ): void {
        self::assertNotNull($domainEntity);
        self::assertEquals($response['id'], $domainEntity->getId()->getValue());

        $jsonKeys = array_keys($response);
        $entityKeys = array_keys($domainEntity->jsonSerialize());
        $defaultedKeys = array_keys(static::getDefaultFieldsValues());
        $remainingKeys = array_diff($entityKeys, $jsonKeys, $defaultedKeys, ['id']);

        foreach ($jsonKeys as $fieldName) {
            $getter = sprintf('get%s', $fieldName);
            self::assertEquals($response[$fieldName], $domainEntity->$getter()->getValue());
        }

        //Defaults are not tested, since this isn't necessarily creation case

        foreach ($remainingKeys as $fieldName) {
            $getter = sprintf('get%s', $fieldName);
            self::assertEmpty($domainEntity->$getter()->getValue());
        }
    }
}
