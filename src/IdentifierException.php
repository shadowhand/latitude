<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

class IdentifierException extends \InvalidArgumentException
{
    const INVALID_IDENTIFIER = 1;

    public static function invalidIdentifier(string $identifier): IdentifierException
    {
        return new static(
            "Invalid SQL identifier: $identifier",
            self::INVALID_IDENTIFIER
        );
    }
}
