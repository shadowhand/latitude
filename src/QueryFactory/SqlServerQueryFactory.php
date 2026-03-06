<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Engine\SqlServerEngine;
use Latitude\QueryBuilder\Query;

class SqlServerQueryFactory implements QueryFactoryInterface
{
    use HasQueryFactoryMethods;

    protected SqlServerEngine $engine;

    public function __construct(?SqlServerEngine $engine = null)
    {
        $this->engine = $engine ?? new SqlServerEngine();
    }

    protected function getEngine(): SqlServerEngine
    {
        return $this->engine;
    }

    public function select(...$columns): Query\SqlServer\SelectQuery
    {
        $query = $this->getEngine()->makeSelect();
        if ($columns) {
            $query = $query->columns(...$columns);
        }

        return $query;
    }

    public function selectDistinct(...$columns): Query\SqlServer\SelectQuery
    {
        return $this->select(...$columns)->distinct();
    }

    public function delete($table): Query\SqlServer\DeleteQuery
    {
        return $this->getEngine()->makeDelete()->from($table);
    }
}
