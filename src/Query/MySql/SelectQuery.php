<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\MySql;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

class SelectQuery extends Query\SelectQuery
{
    /** @var bool */
    protected $calcFoundRows = false;

    public function calcFoundRows(bool $status): self
    {
        $this->calcFoundRows = $status;
        return $this;
    }

    protected function startExpression(): ExpressionInterface
    {
        $query = parent::startExpression();
        if ($this->calcFoundRows) {
            $query = $query->append('SQL_CALC_FOUND_ROWS');
        }
        return $query;
    }
}
