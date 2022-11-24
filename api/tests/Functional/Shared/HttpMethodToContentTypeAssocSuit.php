<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use App\Core\Suit\AbstractAssocSuit;
use App\Core\Suit\HttpMethodsSuit;

class HttpMethodToContentTypeAssocSuit extends AbstractAssocSuit
{
    const HTTP_METHOD_TO_CONTENT_TYPE = [
        HttpMethodsSuit::GET => 'application/json',
        HttpMethodsSuit::POST => 'application/json',
        HttpMethodsSuit::PUT => 'application/json',
        HttpMethodsSuit::DELETE => 'application/json',
        HttpMethodsSuit::PATCH => 'application/merge-patch+json',
    ];


    public static function getValue($key)
    {
        return parent::getValue(strtoupper($key));
    }
}
