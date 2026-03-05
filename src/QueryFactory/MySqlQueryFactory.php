<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Engine\MySqlEngine;
use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query;

class MySqlQueryFactory implements QueryFactoryInterface
{
    use GenericQueryFactoryMethods;

    protected MySqlEngine $engine;

    public function __construct(?MySQLEngine $engine = null)
    {
        $this->engine = $engine ?? new MySqlEngine();
    }

    protected function getEngine(): MySqlEngine
    {
        return $this->engine;
    }

    /**
     * @inheritDoc
     */
    public function select(...$columns): Query\MySql\SelectQuery
    {
        $query = $this->getEngine()->makeSelect();
        if (empty($columns) === false) {
            $query = $query->columns(...$columns);
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function selectDistinct(...$columns): Query\MySql\SelectQuery
    {
        return $this->select(...$columns)->distinct();
    }

    /**
     * @inheritDoc
     */
    public function insert($table, array $map = []): Query\MySql\InsertQuery
    {
        $query = $this->getEngine()->makeInsert()->into($table);

        if ($map) {
            $query = $query->map($map);
        }

        return $query;
    }
}
