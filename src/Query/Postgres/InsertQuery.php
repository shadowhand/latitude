<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Postgres;

use Exception;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\identifyAll;
use function Latitude\QueryBuilder\param;

class InsertQuery extends Query\InsertQuery
{
    use Query\Capability\HasReturning;
    use Query\Capability\HasOnConstraint;

    public function asExpression(): ExpressionInterface
    {
        $query = parent::asExpression();

        $query = $this->applyOnConstraintViolation($query);
        $query = $this->applyReturning($query);

        return $query;
    }

    protected function applyOnConstraintViolation(ExpressionInterface $query): ExpressionInterface
    {
        if (!$this->onConstraint) {
            return $query;
        }

        if ($this->constraint === null) {
            throw new Exception('Postgres requires a constraint to be defined');
        }

        $query = $query->append('ON CONFLICT');


        $query = is_string($this->constraint)
            ? $query->append('%s', identify($this->constraint))
            : $query->append('(%s)', listing(identifyAll($this->constraint)));

        if ($this->ignore === true) {
            return $query->append('DO NOTHING');
        }

        $pattern = '%s = %s';
        $express = static fn($key, $value) => express($pattern, identify($key), param($value));

        return $query->append(
            'DO UPDATE %s',
            listing(
                array_map(
                    $express,
                    array_keys($this->updatesMap),
                    $this->updatesMap
                )
            )
        );
    }
}
