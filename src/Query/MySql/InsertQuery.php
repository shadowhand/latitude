<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\MySql;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

use function array_keys;
use function array_map;
use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\param;

class InsertQuery extends Query\InsertQuery
{
    use Query\Capability\HasOnConstraint;

    /**
     * This method will be deprecated in favor of ->ignoreOnConstraint()
     */
    public function ignore(bool $status): self
    {
        $this->ignore = $status;

        return $this;
    }

    protected function startExpression(): ExpressionInterface
    {
        $query = parent::startExpression();

        $query = $this->applyIgnore($query);

        return $query;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = parent::asExpression();

        $query = $this->applyOnConstraintViolation($query);

        return $query;
    }

    protected function applyIgnore(ExpressionInterface $query): ExpressionInterface
    {
        if ($this->ignore === true) {
            return $query->append('IGNORE');
        }

        return $query;
    }

    protected function applyOnConstraintViolation(ExpressionInterface $query): ExpressionInterface
    {
        if (!$this->onConstraint || $this->ignore === true) {
            return $query;
        }

        return $query->append(
            'ON DUPLICATE KEY UPDATE %s',
            listing(
                array_map(
                    fn($key, $value) => express('%s = %s', identify($key), param($value)),
                    array_keys($this->updatesMap),
                    $this->updatesMap
                )
            )
        );
    }
}
