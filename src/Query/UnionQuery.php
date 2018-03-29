<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;

class UnionQuery extends AbstractQuery
{
    use Capability\CanUnion;
    use Capability\HasOrderBy;

    /** @var bool */
    protected $all = false;

    /** @var StatementInterface */
    protected $left;

    /** @var StatementInterface */
    protected $right;

    public function __construct(
        EngineInterface $engine,
        StatementInterface $left,
        StatementInterface $right
    ) {
        parent::__construct($engine);
        $this->left = $left;
        $this->right = $right;
    }

    public function all($state = true): self
    {
        $this->all = $state;
        return $this;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = $this->startExpression();
        $query = $this->applyAll($query);
        $query = $this->applyRight($query);
        $query = $this->applyOrderBy($query);
        return $query;
    }

    protected function startExpression(): ExpressionInterface
    {
        return express('%s UNION', $this->left);
    }

    protected function applyAll(ExpressionInterface $query): ExpressionInterface
    {
        return $this->all ? $query->append('ALL') : $query;
    }

    protected function applyRight(ExpressionInterface $query): ExpressionInterface
    {
        return $query->append('%s', $this->right);
    }
}
