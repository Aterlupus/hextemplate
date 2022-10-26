<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\Validation;

use App\Core\Util\Set;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class RequestValidator
{
    public static function validate(array $requestContent, array $constraints): array
    {
        return Set::mapKeysAndValues(
            self::getValidationErrors($requestContent, $constraints),
            fn(ConstraintViolation $validationError) => self::getValidationErrorFieldName($validationError),
            fn(ConstraintViolation $validationError) => $validationError->getMessage()
        );
    }

    private static function getValidationErrors(array $requestContent, array $constraints): ConstraintViolationListInterface
    {
        return Validation::createValidator()->validate(
            $requestContent,
            new Assert\Collection($constraints)
        );
    }

    private static function getValidationErrorFieldName(ConstraintViolation $validationError): string
    {
        return trim($validationError->getPropertyPath(), '[]');
    }
}
