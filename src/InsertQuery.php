<?php

namespace Latitude\QueryBuilder;

use InvalidArgumentException;
use Iterator;
class InsertQuery implements Statement
{
    use Traits\CanConvertIteratorToString;
    use Traits\CanUseDefaultIdentifier;
    /**
     * Create a new insert query.
     */
    public static function make($table, array $map = [])
    {
        $query = new static();
        $query->table($table);
        if ($map) {
            $query->map($map);
        }
        return $query;
    }
    /**
     * Set the table to insert into.
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }
    /**
     * Set the columns to insert.
     */
    public function columns(...$columns)
    {
        $this->columns = $columns;
        return $this;
    }
    /**
     * Append values to insert.
     */
    public function values(...$values)
    {
        if (\count($values) !== \count($this->columns)) {
            throw new InvalidArgumentException(sprintf('Number of values (%d) does not match number of columns (%d)', \count($values), \count($this->columns)));
        }
        $this->values[] = ValueList::make($values);
        return $this;
    }
    /**
     * Set the columns and values to insert.
     *
     * NOTE: Existing values will be replaced!
     */
    public function map(array $map)
    {
        $this->values = [];
        $this->columns(...\array_keys($map));
        $this->values(...\array_values($map));
        return $this;
    }
    // Statement
    public function sql(Identifier $identifier = null)
    {
        $identifier = $this->getDefaultIdentifier($identifier);
        return \sprintf('INSERT INTO %s (%s) VALUES %s', $identifier->escapeQualified($this->table), \implode(', ', $identifier->all($this->columns)), $this->stringifyIterator($this->insertLines()));
    }
    // Statement
    public function params()
    {
        // [[a], [b], [c]] -> [a, b, c]
        return \array_merge(...\array_map($this->paramLister(), $this->values));
    }
    /**
     * @var string
     */
    protected $table;
    /**
     * @var array
     */
    protected $columns = [];
    /**
     * @var ValueList[]
     */
    protected $values = [];
    /**
     * Generate a list of insert lines.
     */
    protected function insertLines()
    {
        foreach ($this->values as $line) {
            (yield $line->sql());
        }
    }
    /**
     * Convert all parameters to an array for flattening.
     */
    protected function paramLister()
    {
        return function (ValueList $values) {
            return $values->params();
        };
    }
}