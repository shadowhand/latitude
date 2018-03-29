<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Capability;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\identify;

trait HasFrom
{
    /** @var StatementInterface */
    protected $from;

    public function from($table): self
    {
        $this->from = identify($table);
        return $this;
    }

    protected function applyFrom(ExpressionInterface $query): ExpressionInterface
    {
        return $this->from ? $query->append('FROM %s', $this->from) : $query;
    }
}
