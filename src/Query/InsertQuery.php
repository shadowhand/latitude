<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\identifyAll;
use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\paramAll;

class InsertQuery implements StatementInterface
{
    use Capability\CanExpress;

    /** @var EngineInterface */
    private $engine;

    /** @var StatementInterface */
    private $into;

    /** @var StatementInterface */
    private $columns;

    /** @var StatementInterface[] */
    private $values;

    public function __construct(
        EngineInterface $engine
    ) {
        $this->engine = $engine;
    }

    public function into($table): self
    {
        $copy = clone $this;
        $copy->into = identify($table);
        return $copy;
    }

    public function map(array $map): self
    {
        return $this->columns(...array_keys($map))->values(...array_values($map));
    }

    public function columns(...$columns): self
    {
        $copy = clone $this;
        $copy->columns = listing(identifyAll($columns));
        return $copy;
    }

    public function values(...$params): self
    {
        $copy = clone $this;
        $copy->values[] = express('(%s)', listing(paramAll($params)));
        return $copy;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = express('INSERT');
        $query = $this->applyInto($query);
        $query = $this->applyColumns($query);
        $query = $this->applyValues($query);
        return $query;
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
