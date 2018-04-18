<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\identifyAll;
use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\paramAll;

class InsertQuery extends AbstractQuery
{
    /** @var StatementInterface */
    protected $into;

    /** @var StatementInterface */
    protected $columns;

    /** @var StatementInterface[] */
    protected $values;

    public function into($table): self
    {
        $this->into = identify($table);
        return $this;
    }

    public function map(array $map): self
    {
        return $this->columns(...array_keys($map))->values(...array_values($map));
    }

    public function columns(...$columns): self
    {
        $this->columns = listing(identifyAll($columns));
        return $this;
    }

    public function values(...$params): self
    {
        $this->values[] = express('(%s)', listing(paramAll($params)));
        return $this;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = $this->startExpression();
        $query = $this->applyInto($query);
        $query = $this->applyColumns($query);
        $query = $this->applyValues($query);
        return $query;
    }

    protected function startExpression(): ExpressionInterface
    {
        return express('INSERT');
    }

    protected function applyInto(ExpressionInterface $query): ExpressionInterface
    {
        return $this->into ? $query->append('INTO %s', $this->into) : $query;
    }

    protected function applyColumns(ExpressionInterface $query): ExpressionInterface
    {
        return $this->columns ? $query->append('(%s)', $this->columns) : $query;
    }

    protected function applyValues(ExpressionInterface $query): ExpressionInterface
    {
        return $this->values ? $query->append('VALUES %s', listing($this->values)) : $query;
    }
}
