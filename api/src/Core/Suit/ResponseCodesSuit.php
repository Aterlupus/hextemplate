<?php
declare(strict_types=1);

namespace App\Core\Suit;

use Symfony\Component\HttpFoundation\Response;

class ResponseCodesSuit extends AbstractConstantsSuit
{
    const INFORMATIONAL = [
        Response::HTTP_CONTINUE,
        Response::HTTP_SWITCHING_PROTOCOLS,
        Response::HTTP_PROCESSING,
        Response::HTTP_EARLY_HINTS
    ];
    
    const SUCCESS = [
        Response::HTTP_OK,
        Response::HTTP_CREATED,
        Response::HTTP_ACCEPTED,
        Response::HTTP_NON_AUTHORITATIVE_INFORMATION,
        Response::HTTP_NO_CONTENT,
        Response::HTTP_RESET_CONTENT,
        Response::HTTP_PARTIAL_CONTENT,
        Response::HTTP_MULTI_STATUS,
        Response::HTTP_ALREADY_REPORTED,
        Response::HTTP_IM_USED
    ];
    
    const REDIRECTION = [
        Response::HTTP_MULTIPLE_CHOICES,
        Response::HTTP_MOVED_PERMANENTLY,
        Response::HTTP_FOUND,
        Response::HTTP_SEE_OTHER,
        Response::HTTP_NOT_MODIFIED,
        Response::HTTP_USE_PROXY,
        Response::HTTP_RESERVED,
        Response::HTTP_TEMPORARY_REDIRECT,
        Response::HTTP_PERMANENTLY_REDIRECT
    ];

    const CLIENT_ERRORS = [
        Response::HTTP_BAD_REQUEST,
        Response::HTTP_UNAUTHORIZED,
        Response::HTTP_PAYMENT_REQUIRED,
        Response::HTTP_FORBIDDEN,
        Response::HTTP_NOT_FOUND,
        Response::HTTP_METHOD_NOT_ALLOWED,
        Response::HTTP_NOT_ACCEPTABLE,
        Response::HTTP_PROXY_AUTHENTICATION_REQUIRED,
        Response::HTTP_REQUEST_TIMEOUT,
        Response::HTTP_CONFLICT,
        Response::HTTP_GONE,
        Response::HTTP_LENGTH_REQUIRED,
        Response::HTTP_PRECONDITION_FAILED,
        Response::HTTP_REQUEST_ENTITY_TOO_LARGE,
        Response::HTTP_REQUEST_URI_TOO_LONG,
        Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
        Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE,
        Response::HTTP_EXPECTATION_FAILED,
        Response::HTTP_I_AM_A_TEAPOT,
        Response::HTTP_MISDIRECTED_REQUEST,
        Response::HTTP_UNPROCESSABLE_ENTITY,
        Response::HTTP_LOCKED,
        Response::HTTP_FAILED_DEPENDENCY,
        Response::HTTP_TOO_EARLY,
        Response::HTTP_UPGRADE_REQUIRED,
        Response::HTTP_PRECONDITION_REQUIRED,
        Response::HTTP_TOO_MANY_REQUESTS,
        Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE,
        Response::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS
    ];

    const SERVER_ERRORS = [
        Response::HTTP_INTERNAL_SERVER_ERROR,
        Response::HTTP_NOT_IMPLEMENTED,
        Response::HTTP_BAD_GATEWAY,
        Response::HTTP_SERVICE_UNAVAILABLE,
        Response::HTTP_GATEWAY_TIMEOUT,
        Response::HTTP_VERSION_NOT_SUPPORTED,
        Response::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL,
        Response::HTTP_INSUFFICIENT_STORAGE,
        Response::HTTP_LOOP_DETECTED,
        Response::HTTP_NOT_EXTENDED,
        Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED,
    ];


    public static function isInformational(int $code): bool
    {
        return in_array($code, self::INFORMATIONAL);
    }

    public static function isSuccess(int $code): bool
    {
        return in_array($code, self::SUCCESS);
    }

    public static function isRedirection(int $code): bool
    {
        return in_array($code, self::REDIRECTION);
    }

    public static function isClientError(int $code): bool
    {
        return in_array($code, self::CLIENT_ERRORS);
    }

    public static function isServerError(int $code): bool
    {
        return in_array($code, self::SERVER_ERRORS);
    }
}
