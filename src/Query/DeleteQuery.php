<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\ExpressionInterface;

use function Latitude\QueryBuilder\express;

class DeleteQuery extends AbstractQuery
{
    use Capability\CanExpress;
    use Capability\HasFrom;
    use Capability\HasWhere;

    public function asExpression(): ExpressionInterface
    {
        $query = $this->startExpression();
        $query = $this->applyFrom($query);
        $query = $this->applyWhere($query);
        return $query;
    }

    protected function startExpression(): ExpressionInterface
    {
        return express('DELETE');
    }
}
