<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Capability;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\identify;

trait HasReturning
{
    /** @var StatementInterface */
    protected $returning;

    public function returning($column): self
    {
        $this->returning = identify($column);
        return $this;
    }

    protected function applyReturning(ExpressionInterface $query): ExpressionInterface
    {
        return $this->returning ? $query->append('RETURNING %s', $this->returning) : $query;
    }
}
