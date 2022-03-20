<?php

namespace Stantabcorp\Validator;

class ValidationSettings
{

    private static bool $throwError = false;

    /**
     * @return bool
     */
    public static function isThrowError(): bool
    {
        return self::$throwError;
    }

    /**
     * @param bool $throwError
     */
    public static function setThrowError(bool $throwError): void
    {
        self::$throwError = $throwError;
    }

}