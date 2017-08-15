<?php

namespace Latitude\QueryBuilder;

abstract class LikeValue
{
    /**
     * Escape input for a LIKE condition value.
     */
    public static function escape($value)
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
    public static function any($value)
    {
        $value = static::escape($value);
        return "%{$value}%";
    }
}