<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Capability;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\identify;

trait HasReturning
{
    /** @var StatementInterface */
    private $returning;

    public function returning($column): self
    {
        $copy = clone $this;
        $copy->returning = identify($column);
        return $copy;
    }

    protected function applyReturning(ExpressionInterface $query): ExpressionInterface
    {
        return $this->returning ? $query->append('RETURNING %s', $this->returning) : $query;
    }
}
