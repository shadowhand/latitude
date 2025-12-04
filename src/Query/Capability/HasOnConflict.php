<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Capability;

use Latitude\QueryBuilder\ExpressionInterface;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\func;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\identifyAll;
use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\param;

trait HasOnConflict
{
    protected bool $onConflictDoNothing = false;
    protected bool $onConflictDoUpdate = false;
    protected ?ExpressionInterface $onConflictConstraint = null;
    protected array $onConflictDoUpdateMap = [];

    public function onConflictDoNothing(array $constraint): self
    {
        $this->onConflictDoNothing = true;
        $this->onConflictDoUpdate = false;

        $this->setOnConflictConstraint($constraint);

        return $this;
    }

    public function onConflictDoUpdate(array $constraint, array $map): self
    {
        $this->onConflictDoNothing = false;
        $this->onConflictDoUpdate = true;

        $this->setOnConflictConstraint($constraint);

        $this->onConflictDoUpdateMap = $map;

        return $this;
    }

    protected function setOnConflictConstraint(array $constraint): void
    {
        $this->onConflictConstraint = express(
            '(%s)',
            listing(identifyAll($constraint))
        );
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

        $express = static function ($key, $value): ExpressionInterface {
            $keyIsIdentifier = !is_numeric($key);

            $identifier = $keyIsIdentifier ? $key : $value;

            if (!$keyIsIdentifier) {
                return express(
                    '%s = EXCLUDED.%s',
                    identify($identifier),
                    identify($identifier)
                );
            }

            return express('%s = %s', identify($key), param($value));
        };

        return $query->append(
            'DO UPDATE SET %s',
            listing(
                array_map(
                    $express,
                    array_keys($this->onConflictDoUpdateMap),
                    $this->onConflictDoUpdateMap
                )
            )
        );
    }
}
