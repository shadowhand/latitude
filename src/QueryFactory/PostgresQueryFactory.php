<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Engine\PostgresEngine;
use Latitude\QueryBuilder\Query;

class PostgresQueryFactory implements QueryFactoryInterface
{
    use GenericQueryFactoryMethods;

    protected PostgresEngine $engine;

    public function __construct(?PostgresEngine $engine = null)
    {
        $this->engine = $engine ?? new PostgresEngine();
    }

    protected function getEngine(): PostgresEngine
    {
        return $this->engine;
    }

    /**
     * @inheritDoc
     */
    public function insert($table, array $map = []): Query\Postgres\InsertQuery
    {
        $query = $this->getEngine()->makeInsert()->into($table);

        if ($map) {
            $query = $query->map($map);
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function update($table, array $map = []): Query\Postgres\UpdateQuery
    {
        $query = $this->getEngine()->makeUpdate()->table($table);

        if ($map) {
            $query = $query->set($map);
        }

        return $query;
    }
}
