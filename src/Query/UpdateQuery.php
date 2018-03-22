<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\QueryInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\listing;
use function Latitude\QueryBuilder\param;

class UpdateQuery implements QueryInterface
{
    use Capability\CanExpress;
    use Capability\HasWhere;

    /** @var EngineInterface */
    private $engine;

    /** @var StatementInterface */
    private $table;

    /** @var StatementInterface */
    private $set;

    public function __construct(
        EngineInterface $engine
    ) {
        $this->engine = $engine;
    }

    public function table($table): self
    {
        $copy = clone $this;
        $copy->table = identify($table);
        return $copy;
    }

    public function set(array $map): self
    {
        $copy = clone $this;
        $copy->set = listing(array_map(
            function ($key, $value): StatementInterface {
                return express('%s = %s', identify($key), param($value));
            },
            array_keys($map),
            $map
        ));
        return $copy;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = express('UPDATE');
        $query = $this->applyTable($query);
        $query = $this->applySet($query);
        $query = $this->applyWhere($query);

        return $query;
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
