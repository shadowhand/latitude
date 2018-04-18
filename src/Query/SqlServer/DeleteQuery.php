<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\SqlServer;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

use function Latitude\QueryBuilder\literal;

class DeleteQuery extends Query\DeleteQuery
{
    protected function startExpression(): ExpressionInterface
    {
        $query = parent::startExpression();
        if (is_int($this->limit)) {
            $query = $query->append('TOP(%d)', literal($this->limit));
        }
        return $query;
    }

    protected function applyLimit(ExpressionInterface $query): ExpressionInterface
    {
        return $query;
    }
}
