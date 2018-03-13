<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\CriteriaInterface;
use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\identifyAll;
use function Latitude\QueryBuilder\listing;

class SelectQuery implements StatementInterface
{
    use Capability\CanExpress;
    use Capability\HasFrom;
    use Capability\HasOrderBy;
    use Capability\HasWhere;

    /** @var EngineInterface */
    private $engine;

    /** @var bool */
    private $distinct = false;

    /** @var StatementInterface */
    private $columns;

    /** @var StatementInterface[] */
    private $joins = [];

    /** @var StatementInterface[] */
    private $groupBy = [];

    /** @var CriteriaInterface */
    private $having;

    public function __construct(
        EngineInterface $engine
    ) {
        $this->engine = $engine;
    }

    public function distinct($state = true): self
    {
        $copy = clone $this;
        $copy->distinct = $state;
        return $copy;
    }

    public function columns(...$columns): self
    {
        $copy = clone $this;
        $copy->columns = listing(identifyAll($columns));
        return $copy;
    }

    public function join($table, CriteriaInterface $criteria, string $type = ''): self
    {
        $copy = clone $this;
        $copy->joins[] = express(trim("$type JOIN %s ON %s"), identify($table), $criteria);
        return $copy;
    }

    public function groupBy(...$columns): self
    {
        $copy = clone $this;
        $copy->groupBy = identifyAll($columns);
        return $copy;
    }

    public function having(CriteriaInterface $criteria): self
    {
        $copy = clone $this;
        $copy->having = $criteria;
        return $copy;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = express('SELECT');
        $query = $this->applyDistinct($query);
        $query = $this->applyColumns($query);
        $query = $this->applyFrom($query);
        $query = $this->applyJoins($query);
        $query = $this->applyWhere($query);
        $query = $this->applyGroupBy($query);
        $query = $this->applyHaving($query);
        $query = $this->applyOrderBy($query);

        return $query;
    }

    protected function applyDistinct(ExpressionInterface $query): ExpressionInterface
    {
        return $this->distinct ? $query->append('DISTINCT') : $query;
    }

    protected function applyColumns(ExpressionInterface $query): ExpressionInterface
    {
        return $this->columns ? $query->append('%s', $this->columns) : $query->append('*');
    }

    protected function applyJoins(ExpressionInterface $query): ExpressionInterface
    {
        return $this->joins ? $query->append('%s', listing($this->joins, ' ')) : $query;
    }

    protected function applyGroupBy(ExpressionInterface $query): ExpressionInterface
    {
        return $this->groupBy ? $query->append('GROUP BY %s', listing($this->groupBy)) : $query;
    }

    protected function applyHaving(ExpressionInterface $query): ExpressionInterface
    {
        return $this->having ? $query->append('HAVING %s', $this->having) : $query;
    }
}
