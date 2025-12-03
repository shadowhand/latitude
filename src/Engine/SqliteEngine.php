<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\Query;

use function is_bool;

class SqliteEngine extends CommonEngine
{
    public function makeInsert(): Query\InsertQuery
    {
        return new Query\Sqlite\InsertQuery($this);
    }

    public function makeUpdate(): Query\UpdateQuery
    {
        return new Query\Sqlite\UpdateQuery($this);
    }

    /**
     * @inheritDoc
     */
    public function exportParameter($param): string
    {
        if (is_bool($param)) {
            // SQLite does not have a boolean storage class, so we use 1/0 instead of true/false.
            return (string) (int) $param;
        }

        return parent::exportParameter($param);
    }
}
