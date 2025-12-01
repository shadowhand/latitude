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
    protected bool $ignore = false;
    protected bool $onDuplicateKeyUpdate = false;
    protected array $onDuplicateKeyUpdatesMap = [];

    public function ignore(bool $status): self
    {
        $this->ignore = $status;

        if ($status && $this->onDuplicateKeyUpdate) {
            $this->onDuplicateKeyUpdate = false;
        }

        return $this;
    }

    public function onDuplicateKeyUpdate(array $map): self
    {
        $this->ignore = false;
        $this->onDuplicateKeyUpdate = true;
        $this->onDuplicateKeyUpdatesMap = $map;

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

        $query = $this->applyOnDuplicateKeyUpdate($query);

        return $query;
    }

    protected function applyIgnore(ExpressionInterface $query): ExpressionInterface
    {
        if ($this->ignore === true) {
            return $query->append('IGNORE');
        }

        return $query;
    }

    protected function applyOnDuplicateKeyUpdate(ExpressionInterface $query): ExpressionInterface
    {
        if (!$this->onDuplicateKeyUpdate) {
            return $query;
        }

        return $query->append(
            'ON DUPLICATE KEY UPDATE %s',
            listing(
                array_map(
                    fn($key, $value) => express('%s = %s', identify($key), param($value)),
                    array_keys($this->onDuplicateKeyUpdatesMap),
                    $this->onDuplicateKeyUpdatesMap
                )
            )
        );
    }
}
