<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\Query;

class PostgresEngine extends CommonEngine
{
    public function insert($table, array $map = []): Query\InsertQuery
    {
        $query = new Query\Postgres\InsertQuery($this);
        if (empty($table) === false) {
            $query = $query->into($table);
        }
        if (empty($map) === false) {
            $query = $query->map($map);
        }
        return $query;
    }

    public function update($table, array $map = []): Query\UpdateQuery
    {
        $query = new Query\Postgres\UpdateQuery($this);
        $query = $query->table($table);
        if (empty($map) === false) {
            $query = $query->set($map);
        }
        return $query;
    }
}
