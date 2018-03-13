<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Capability;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\order;

trait HasOrderBy
{
    /** @var StatementInterface[] */
    private $orderBy;

    public function orderBy($column, string $direction = ''): self
    {
        $copy = clone $this;
        $copy->orderBy[] = order($column, $direction);
        return $copy;
    }

    protected function applyOrderBy(ExpressionInterface $query): ExpressionInterface
    {
        return $this->orderBy ? $query->append('ORDER BY %s', listing($this->orderBy)) : $query;
    }
}
