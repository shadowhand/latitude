<?php

namespace Latitude\QueryBuilder\Connectors;

use mysqli;
use mysqli_stmt;
use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;
use function gettype;

class MySqliConnector extends mysqli
{
    public function createStatementFromQuery(EngineInterface $engine, AbstractQuery $query): mysqli_stmt
    {
        $statement = $this->prepare($query->sql($engine));

        $types = [];
        $values = [];

        foreach ($query->params($engine) as $i => $value) {
            $types[] = [
                'NULL' => 's',
                'integer' => 'i',
                'float' => 'd',
                'string' => 's',
            ][gettype($value)] ?? 's';

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
