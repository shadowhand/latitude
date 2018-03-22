<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;

class UnionQuery implements StatementInterface
{
    use Capability\CanExpress;
    use Capability\CanUnion;
    use Capability\HasOrderBy;

    /** @var EngineInterface */
    private $engine;

    /** @var bool */
    private $all = false;

    /** @var StatementInterface */
    private $left;

    /** @var StatementInterface */
    private $right;

    public function __construct(
        EngineInterface $engine,
        StatementInterface $left,
        StatementInterface $right
    ) {
        $this->engine = $engine;
        $this->left = $left;
        $this->right = $right;
    }

    public function all($state = true): self
    {
        $copy = clone $this;
        $copy->all = $state;
        return $copy;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = express('%s UNION', $this->left);
        $query = $this->applyAll($query);
        $query = $this->applyRight($query);
        $query = $this->applyOrderBy($query);
        return $query;
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
