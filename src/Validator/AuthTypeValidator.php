<?php
namespace KSearchClient\Validator;

use Http\Message\Authentication;
use Http\Message\Authentication\BasicAuth;
use Http\Message\Authentication\Bearer;

class AuthTypeValidator
{
    const SUPPORTED_AUTHENTICATION_METHODS = [
        BasicAuth::class,
        Bearer::class
    ];

    public static function isSupported(Authentication $auth): bool
    {
        foreach( self::SUPPORTED_AUTHENTICATION_METHODS as $authenticationMethod ) {
            if ($auth instanceof $authenticationMethod) {
                return true;
            }
        }
        return false;
    }

}