<?php

namespace Latitude\QueryBuilder\Connectors;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;
use SQLite3Stmt;

class SQLite3 extends \SQLite3
{
    public function createStatementFromQuery(EngineInterface $engine, AbstractQuery $query): SQLite3Stmt
    {
        $statement = $this->prepare($query->sql($engine));

        foreach ($query->params($engine) as $i => $value) {
            $statement->bindValue(
                $i + 1,
                $value,
                [
                    'null' => SQLITE3_NULL,
                    'int' => SQLITE3_INTEGER,
                    'float' => SQLITE3_FLOAT,
                    'string' => SQLITE3_TEXT
                ][get_debug_type($value)] ?? SQLITE3_TEXT
            );
        }

        return $statement;
    }
}
