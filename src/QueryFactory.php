<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Latitude\QueryBuilder\Ruler\Context;

class QueryFactory
{
    /** @var EngineInterface */
    protected $engine;

    public function __construct(
        EngineInterface $engine = null
    ) {
        $this->engine = $engine ?: new Engine\BasicEngine();
    }

    /**
     * Create a new SELECT query
     *
     * @param string|StatementInterface ...$columns
     */
    public function select(...$columns): Query\SelectQuery
    {
        $query = $this->engine->makeSelect();
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
        $query = $this->engine->makeInsert()->into($table);
        if (empty($map) === false) {
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
        return $this->engine->makeDelete()->from($table);
    }

    /**
     * Create a new UPDATE query
     *
     * @param string|StatementInterface $table
     */
    public function update($table, array $map = []): Query\UpdateQuery
    {
        $query = $this->engine->makeUpdate()->table($table);
        if (empty($map) === false) {
            $query = $query->set($map);
        }
        return $query;
    }
}
