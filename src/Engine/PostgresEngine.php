<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\Query;

class PostgresEngine extends CommonEngine
{
    public const DATETIME_FORMAT = 'Y-m-d H:i:s.u';

    public function makeInsert(): Query\Postgres\InsertQuery
    {
        return new Query\Postgres\InsertQuery($this);
    }

    public function makeUpdate(): Query\Postgres\UpdateQuery
    {
        return new Query\Postgres\UpdateQuery($this);
    }
}
