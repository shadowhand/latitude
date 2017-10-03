<?php

namespace Latitude\QueryBuilder;

class IdentifierException extends \InvalidArgumentException
{
    const INVALID_IDENTIFIER = 1;
    public static function invalidIdentifier($identifier)
    {
        return new static("Invalid SQL identifier: {$identifier}", self::INVALID_IDENTIFIER);
    }
}