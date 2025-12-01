<?php

namespace Latitude\QueryBuilder\Connectors;

use Latitude\QueryBuilder\EngineInterface;
use PDOStatement;
use Latitude\QueryBuilder\Query\AbstractQuery;

class PDO extends \PDO
{
    public function createStatementFromQuery(EngineInterface $engine, AbstractQuery $query): PDOStatement
    {
        $statement = $this->prepare($query->sql($engine));

        foreach ($query->params($engine) as $i => $value) {
            $statement->bindValue(
                $i + 1,
                $value,
                [
                    'null' => static::PARAM_NULL,
                    'bool' => static::PARAM_BOOL,
                    'int' => static::PARAM_INT,
                    'float' => static::PARAM_STR,
                    'string' => static::PARAM_STR,
                ][get_debug_type($value)] ?? static::PARAM_STR
            );
        }

        return $statement;
    }
}
