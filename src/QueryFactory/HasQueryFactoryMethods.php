<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;
use Latitude\QueryBuilder\Query;

trait HasQueryFactoryMethods
{
    abstract protected function getEngine(): EngineInterface;

    /**
     * Create a new SELECT query
     *
     * @param string|StatementInterface ...$columns
     */
    public function select(...$columns): Query\SelectQuery
    {
        $query = $this->getEngine()->makeSelect();
        if (empty($columns) === false) {
            $query = $query->columns(...$columns);
        }

        return $query;
    }

    /**
     * Create a new SELECT DISTINCT query
     *
     * @param string|StatementInterface ...$columns
     */
    public function selectDistinct(...$columns): Query\SelectQuery
    {
        return $this->select(...$columns)->distinct();
    }

    /**
     * Create a new INSERT query
     *
     * @param string|StatementInterface $table
     */
    public function insert($table, array $map = []): Query\InsertQuery
    {
        $query = $this->getEngine()->makeInsert()->into($table);

        if ($map) {
            $query = $query->map($map);
        }

        return $query;
    }

    /**
     * Create a new DELETE query
     *
     * @param string|StatementInterface $table
     */
    public function delete($table): Query\DeleteQuery
    {
        return $this->getEngine()->makeDelete()->from($table);
    }

    /**
     * Create a new UPDATE query
     *
     * @param string|StatementInterface $table
     */
    public function update($table, array $map = []): Query\UpdateQuery
    {
        $query = $this->getEngine()->makeUpdate()->table($table);

        if ($map) {
            $query = $query->set($map);
        }

        return $query;
    }
}
