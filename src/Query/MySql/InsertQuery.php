<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\MySql;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

class InsertQuery extends Query\InsertQuery
{
    /** @var bool */
    protected $ignore = false;

    public function ignore(bool $status): self
    {
        $this->ignore = $status;
        return $this;
    }

    protected function startExpression(): ExpressionInterface
    {
        $query = parent::startExpression();
        if ($this->ignore) {
            $query = $query->append('IGNORE');
        }
        return $query;
    }
}
