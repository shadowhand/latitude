<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use RuntimeException;

class QueryBuilderException extends RuntimeException
{
    const UPDATE_REQUIRES_WHERE = 1;

    public static function updateRequiresWhere(): QueryBuilderException
    {
        return new static(
            'UPDATE queries require a WHERE clause',
            self::UPDATE_REQUIRES_WHERE
        );
    }
}
