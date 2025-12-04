<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Firebird;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

use function is_int;
use function Latitude\QueryBuilder\literal;

class SelectQuery extends Query\SelectQuery
{
    protected function applyOffset(ExpressionInterface $query): ExpressionInterface
    {
        if (!is_int($this->limit) || !is_int($this->offset)) {
            return $query;
        }

        $rows = $this->offset + 1;
        $to = $rows + $this->limit - 1;

        return $query->append('ROWS %d TO %d', literal($rows), literal($to));
    }

    protected function applyLimit(ExpressionInterface $query): ExpressionInterface
    {
        if (!is_int($this->limit)) {
            return $query;
        }

        // When offset is present, ROWS acts differently and should be handled in ->applyOffset()
        if (is_int($this->offset)) {
            return $query;
        }

        return $query->append('ROWS %s', literal($this->limit));
    }
}
