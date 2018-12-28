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
    protected $orderBy;

    public function orderBy($column, string $direction = ''): self
    {
        if (empty($column)) {
            $this->orderBy = [];
            return $this;
        }
        
        $this->orderBy[] = order($column, $direction);
        return $this;
    }

    protected function applyOrderBy(ExpressionInterface $query): ExpressionInterface
    {
        return $this->orderBy ? $query->append('ORDER BY %s', listing($this->orderBy)) : $query;
    }
}
