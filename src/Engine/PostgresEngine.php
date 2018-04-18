<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\Query;

class PostgresEngine extends CommonEngine
{
    public function makeInsert(): Query\InsertQuery
    {
        return new Query\Postgres\InsertQuery($this);
    }

    public function makeUpdate(): Query\UpdateQuery
    {
        return new Query\Postgres\UpdateQuery($this);
    }
}
