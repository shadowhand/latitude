<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Capability;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\identifyAll;
use function Latitude\QueryBuilder\listing;

trait HasFrom
{
    /** @var StatementInterface[] */
    protected $from = [];

    public function from(...$tables): self
    {
        $this->from = identifyAll($tables);
        return $this;
    }

    public function addFrom(...$tables): self
    {
        return $this->from(...array_merge($this->from, $tables));
    }

    protected function applyFrom(ExpressionInterface $query): ExpressionInterface
    {
        return $this->from ? $query->append('FROM %s', listing($this->from)) : $query;
    }
}
