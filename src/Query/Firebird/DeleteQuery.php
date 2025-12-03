<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Firebird;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

use function is_int;
use function Latitude\QueryBuilder\literal;

class DeleteQuery extends Query\DeleteQuery
{
    protected function applyLimit(ExpressionInterface $query): ExpressionInterface
    {
        if (!is_int($this->limit)) {
            return $query;
        }

        return $query->append('ROWS %s', literal($this->limit));
    }
}
