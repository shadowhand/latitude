<?php

namespace Latitude\QueryBuilder;

function isQuery($value)
{
    return $value instanceof Query;
}
function isStatement($value)
{
    return $value instanceof Statement;
}
function reference($sql)
{
    if (isStatement($sql)) {
        return $sql;
    }
    if (strpos($sql, ' ') === false) {
        return Reference::make($sql);
    }
    return Alias::make(...\preg_split('/ (?:AS )?/i', $sql));
}