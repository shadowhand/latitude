<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\param;

class UpdateQuery extends AbstractQuery
{
    use Capability\HasWhere;

    /** @var StatementInterface */
    protected $table;

    /** @var StatementInterface */
    protected $set;

    public function table($table): self
    {
        $this->table = identify($table);
        return $this;
    }

    public function set(array $map): self
    {
        $this->set = listing(array_map(
            function ($key, $value): StatementInterface {
                return express('%s = %s', identify($key), param($value));
            },
            array_keys($map),
            $map
        ));
        return $this;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = $this->startExpression();
        $query = $this->applyTable($query);
        $query = $this->applySet($query);
        $query = $this->applyWhere($query);

        return $query;
    }

    protected function startExpression(): ExpressionInterface
    {
        return express('UPDATE');
    }

    protected function applyTable(ExpressionInterface $query): ExpressionInterface
    {
        return $this->table ? $query->append('%s', $this->table) : $query;
    }

    protected function applySet(ExpressionInterface $query): ExpressionInterface
    {
        return $this->set ? $query->append('SET %s', $this->set): $query;
    }
}
