<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Sqlite;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

class InsertQuery extends Query\InsertQuery
{
    use Query\Capability\HasOnConflict;
    use Query\Capability\HasReturning;

    public function asExpression(): ExpressionInterface
    {
        $query = parent::asExpression();

        if ($this->supportsOnConflict()) {
            $query = $this->applyOnConstraintViolation($query);
        }

        if ($this->supportsReturning()) {
            $query = $this->applyReturning($query);
        }

        return $query;
    }

    protected function supportsOnConflict(): bool
    {
        return PHP_VERSION_ID >= 80000;
    }

    protected function supportsReturning(): bool
    {
        return PHP_VERSION_ID >= 80100;
    }
}
