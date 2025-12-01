<?php

namespace Latitude\QueryBuilder\Connectors;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query\AbstractQuery;
use mysqli_stmt;

class mysqli extends \mysqli
{
    public function createStatementFromQuery(EngineInterface $engine, AbstractQuery $query): mysqli_stmt
    {
        $statement = $this->prepare($query->sql($engine));

        $types = [];
        $values = [];

        foreach ($query->params($engine) as $i => $value) {
            $types[] = [
                'null' => 's',
                'int' => 'i',
                'float' => 'd',
                'string' => 's',
            ][get_debug_type($value)] ?? 's';

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
