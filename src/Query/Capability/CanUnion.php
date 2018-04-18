<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Capability;

use Latitude\QueryBuilder\Query\UnionQuery;
use Latitude\QueryBuilder\StatementInterface;

trait CanUnion
{
    public function union(StatementInterface $right): UnionQuery
    {
        return new UnionQuery($this->engine, $this, $right);
    }

    public function unionAll(StatementInterface $right): UnionQuery
    {
        return $this->union($right)->all();
    }
}
