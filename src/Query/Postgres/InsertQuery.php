<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Postgres;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

use function is_string;
use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\identifyAll;
use function Latitude\QueryBuilder\param;

class InsertQuery extends Query\InsertQuery
{
    use Query\Capability\HasReturning;

    protected bool $onConflictDoNothing = false;
    protected bool $onConflictDoUpdate = false;
    protected ?ExpressionInterface $onConflictConstraint = null;
    protected array $onDuplicateKeyUpdatesMap = [];

    /**
     * @param array|string $constraint
     */
    public function onConflictDoNothing($constraint): self
    {
        $this->onConflictDoNothing = true;
        $this->onConflictDoUpdate = false;

        $this->setOnConflictConstraint($constraint);

        return $this;
    }

    /**
     * @param array|string $constraint
     * @param array $updatesMap
     */
    public function onConflictDoUpdate($constraint, array $updatesMap): self
    {
        $this->onConflictDoNothing = false;
        $this->onConflictDoUpdate = true;

        $this->setOnConflictConstraint($constraint);

        $this->onDuplicateKeyUpdatesMap = $updatesMap;

        return $this;
    }

    /**
     * @param array|string $constraint
     */
    protected function setOnConflictConstraint($constraint): void
    {
        $this->onConflictConstraint = is_string($constraint)
            ? express('ON CONSTRAINT %s', identify($constraint))
            : express('(%s)', listing(identifyAll($constraint)));
    }

    public function asExpression(): ExpressionInterface
    {
        $query = parent::asExpression();

        $query = $this->applyOnConstraintViolation($query);
        $query = $this->applyReturning($query);

        return $query;
    }

    protected function applyOnConstraintViolation(ExpressionInterface $query): ExpressionInterface
    {
        if (!$this->onConflictDoNothing && !$this->onConflictDoUpdate) {
            return $query;
        }

        $query = $query->append('ON CONFLICT %s', $this->onConflictConstraint);

        if ($this->onConflictDoNothing) {
            return $query->append('DO NOTHING');
        }

        $pattern = '%s = %s';
        $express = static fn($key, $value) => express($pattern, identify($key), param($value));

        return $query->append(
            'DO UPDATE %s',
            listing(
                array_map(
                    $express,
                    array_keys($this->onDuplicateKeyUpdatesMap),
                    $this->onDuplicateKeyUpdatesMap
                )
            )
        );
    }
}
