<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\CriteriaInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\identifyAll;
use function Latitude\QueryBuilder\listing;

class SelectQuery extends AbstractQuery
{
    use Capability\CanUnion;
    use Capability\HasFrom;
    use Capability\HasLimit;
    use Capability\HasOffset;
    use Capability\HasOrderBy;
    use Capability\HasWhere;

    /** @var bool */
    protected $distinct = false;

    /** @var StatementInterface[] */
    protected $columns = [];

    /** @var StatementInterface[] */
    protected $joins = [];

    /** @var StatementInterface[] */
    protected $groupBy = [];

    /** @var CriteriaInterface */
    protected $having;

    public function distinct($state = true): self
    {
        $this->distinct = $state;
        return $this;
    }

    public function columns(...$columns): self
    {
        $this->columns = identifyAll($columns);
        return $this;
    }

    public function addColumns(...$columns): self
    {
        return $this->columns(...array_merge($this->columns, $columns));
    }

    public function join($table, CriteriaInterface $criteria, string $type = ''): self
    {
        $join = trim(sprintf('%s JOIN', strtoupper($type)));
        $this->joins[] = express("$join %s ON %s", identify($table), $criteria);
        return $this;
    }

    public function innerJoin($table, CriteriaInterface $criteria): self
    {
        return $this->join($table, $criteria, 'INNER');
    }

    public function leftJoin($table, CriteriaInterface $criteria): self
    {
        return $this->join($table, $criteria, 'LEFT');
    }

    public function rightJoin($table, CriteriaInterface $criteria): self
    {
        return $this->join($table, $criteria, 'RIGHT');
    }

    public function fullJoin($table, CriteriaInterface $criteria): self
    {
        return $this->join($table, $criteria, 'FULL');
    }

    public function groupBy(...$columns): self
    {
        $this->groupBy = identifyAll($columns);
        return $this;
    }

    public function having(CriteriaInterface $criteria): self
    {
        $this->having = $criteria;
        return $this;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = $this->startExpression();
        $query = $this->applyDistinct($query);
        $query = $this->applyColumns($query);
        $query = $this->applyFrom($query);
        $query = $this->applyJoins($query);
        $query = $this->applyWhere($query);
        $query = $this->applyGroupBy($query);
        $query = $this->applyHaving($query);
        $query = $this->applyOrderBy($query);
        $query = $this->applyLimit($query);
        $query = $this->applyOffset($query);

        return $query;
    }

    protected function startExpression(): ExpressionInterface
    {
        return express('SELECT');
    }

    protected function applyDistinct(ExpressionInterface $query): ExpressionInterface
    {
        return $this->distinct ? $query->append('DISTINCT') : $query;
    }

    protected function applyColumns(ExpressionInterface $query): ExpressionInterface
    {
        return $this->columns ? $query->append('%s', listing($this->columns)) : $query->append('*');
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
