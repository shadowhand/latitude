<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Capability;

use Latitude\QueryBuilder\ExpressionInterface;

use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\order;

trait HasOrderBy
{
    protected array $orderBy = [];

    /**
     * @param mixed $column
     */
    public function orderBy($column, string $direction = ''): self
    {
        if (! $column) {
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
