<?php

namespace Latitude\QueryBuilder\Connectors;

use SQLite3;
use SQLite3Stmt;
use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;

use function gettype;

class SQLite3Connector extends SQLite3
{
    protected static $typeMap = [
        'NULL' => SQLITE3_NULL,
        'integer' => SQLITE3_INTEGER,
        'double' => SQLITE3_FLOAT,
        'string' => SQLITE3_TEXT
    ];
    protected static $defaultType = SQLITE3_TEXT;

    public function createStatementFromQuery(EngineInterface $engine, AbstractQuery $query): SQLite3Stmt
    {
        $statement = $this->prepare($query->sql($engine));

        foreach ($query->params($engine) as $i => $value) {
            $statement->bindValue(
                $i + 1,
                $value,
                static::$typeMap[gettype($value)] ?? static::$defaultType
            );
        }

        return $statement;
    }
}
