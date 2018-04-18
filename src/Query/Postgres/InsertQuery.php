<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Postgres;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

class InsertQuery extends Query\InsertQuery
{
    use Query\Capability\HasReturning;

    public function asExpression(): ExpressionInterface
    {
        $query = parent::asExpression();
        $query = $this->applyReturning($query);

        return $query;
    }
}
