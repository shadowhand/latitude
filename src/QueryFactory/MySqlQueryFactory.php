<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Engine\MySqlEngine;
use Latitude\QueryBuilder\Query;

class MySqlQueryFactory implements QueryFactoryInterface
{
    use HasQueryFactoryMethods;

    protected MySqlEngine $engine;

    public function __construct(?MySqlEngine $engine = null)
    {
        $this->engine = $engine ?? new MySqlEngine();
    }

    protected function getEngine(): MySqlEngine
    {
        return $this->engine;
    }

    public function select(...$columns): Query\MySql\SelectQuery
    {
        $query = $this->getEngine()->makeSelect();
        if ($columns) {
            $query = $query->columns(...$columns);
        }

        return $query;
    }

    public function selectDistinct(...$columns): Query\MySql\SelectQuery
    {
        return $this->select(...$columns)->distinct();
    }

    public function insert($table, array $map = []): Query\MySql\InsertQuery
    {
        $query = $this->getEngine()->makeInsert()->into($table);

        if ($map) {
            $query = $query->map($map);
        }

        return $query;
    }
}
