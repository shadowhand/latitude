<?php

namespace Latitude\QueryBuilder\Connectors;

use PDO;
use PDOStatement;
use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;
use function gettype;

class PDOConnector extends PDO
{
    public function createStatementFromQuery(EngineInterface $engine, AbstractQuery $query): PDOStatement
    {
        $statement = $this->prepare($query->sql($engine));

        foreach ($query->params($engine) as $i => $value) {
            $statement->bindValue(
                $i + 1,
                $value,
                [
                    'NULL' => static::PARAM_NULL,
                    'boolean' => static::PARAM_BOOL,
                    'integer' => static::PARAM_INT,
                    'double' => static::PARAM_STR,
                    'string' => static::PARAM_STR,
                ][gettype($value)] ?? static::PARAM_STR
            );
        }

        return $statement;
    }
}
