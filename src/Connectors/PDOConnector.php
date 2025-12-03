<?php

namespace Latitude\QueryBuilder\Connectors;

use PDO;
use PDOStatement;
use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;

use function gettype;

class PDOConnector extends PDO
{
    protected static $typeMap = [
        'NULL' => self::PARAM_NULL,
        'boolean' => self::PARAM_BOOL,
        'integer' => self::PARAM_INT,
        'double' => self::PARAM_STR,
        'string' => self::PARAM_STR
    ];
    protected static $defaultType = self::PARAM_STR;

    public function createStatementFromQuery(EngineInterface $engine, AbstractQuery $query): PDOStatement
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
