<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

/**
 * @param mixed $value
 * @return bool
 */
function isQuery($value): bool
{
    return $value instanceof Query;
}

/**
 * @param mixed $value
 * @return bool
 */
function isStatement($value): bool
{
    return $value instanceof Statement;
}

/**
 * @param Statement|string $sql
 * @return Statement
 * @throws \TypeError
 */
function reference($sql): Statement
{
    if ($sql instanceof Statement) {
        return $sql;
    }
    if (!\is_string($sql)) {
        throw new \TypeError('reference() expects a string or Statement');
    }

    if (strpos($sql, ' ') === false) {
        return Reference::make($sql);
    }

    return Alias::make(...\preg_split('/ (?:AS )?/i', $sql));
}
