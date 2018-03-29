<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\SqlServer;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

use function Latitude\QueryBuilder\literal;

class SelectQuery extends Query\SelectQuery
{
    protected function applyOffset(ExpressionInterface $query): ExpressionInterface
    {
        if (is_int($this->offset) && is_int($this->limit)) {
            return $query->append('OFFSET %d ROWS FETCH NEXT %d ROWS ONLY', literal($this->offset), literal($this->limit));
        }

        return $query;
    }

    protected function applyLimit(ExpressionInterface $query): ExpressionInterface
    {
        // SQL Server requires that OFFSET be defined to use LIMIT
        return $query;
    }
}
