<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

abstract class Escape
{
    /**
     * Escape input for a LIKE condition value.
     */
    public static function like(string $value): string
    {
        // Backslash is used to escape wildcards.
        $value = str_replace('\\', '\\\\', $value);
        // Standard wildcards are underscore and percent sign.
        $value = str_replace('%', '\\%', $value);
        $value = str_replace('_', '\\_', $value);

        return $value;
    }

    /**
     * Escape input for a LIKE condition, surrounding with wildcards.
     */
    public static function likeAny(string $value): string
    {
        $value = static::like($value);
        return "%$value%";
    }
}
