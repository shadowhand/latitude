<?php

namespace Latitude\QueryBuilder\Connectors;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;

use mysqli;
use mysqli_stmt;

use function gettype;

class MySqliConnector extends mysqli
{
    public const MYSQLI_INT = 'i';
    public const MYSQLI_FLOAT = 'd';
    public const MYSQLI_STRING = 's';

    protected static $typeMap = [
        'NULL' => self::MYSQLI_STRING,
        'integer' => self::MYSQLI_INT,
        'float' => self::MYSQLI_FLOAT,
        'string' => self::MYSQLI_STRING
    ];
    protected static $defaultType = self::MYSQLI_STRING;

    public function createStatementFromQuery(EngineInterface $engine, AbstractQuery $query): mysqli_stmt
    {
        $statement = $this->prepare($query->sql($engine));

        $types = [];
        $values = [];

        foreach ($query->params($engine) as $value) {
            $types[] = static::$typeMap[gettype($value)] ?? static::$defaultType;
            $values[] = $value;
        }

        $statement->bind_param(
            implode(
                '',
                $types
            ),
            ...$values
        );

        return $statement;
    }
}
