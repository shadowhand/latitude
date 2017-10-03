<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

function isQuery($value): bool
{
    return $value instanceof Query;
}

function isStatement($value): bool
{
    return $value instanceof Statement;
}

function reference($sql): Statement
{
    if (isStatement($sql)) {
        return $sql;
    }

    if (strpos($sql, ' ') === false) {
        return Reference::make($sql);
    }

    return Alias::make(...\preg_split('/ (?:AS )?/i', $sql));
}
