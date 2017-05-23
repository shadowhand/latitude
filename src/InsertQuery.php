<?php

namespace Latitude\QueryBuilder;

use Iterator;
class InsertQuery implements Statement
{
    use Traits\CanConvertIteratorToString;
    use Traits\CanReplaceBooleanAndNullValues;
    use Traits\CanUseDefaultIdentifier;
    /**
     * Create a new insert query.
     */
    public static function make($table, array $map)
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
     * Set the columns and values to insert.
     */
    public function map(array $map)
    {
        $this->columns = \array_keys($map);
        $this->params = \array_values($map);
        return $this;
    }
    // Statement
    public function sql(Identifier $identifier = null)
    {
        $identifier = $this->getDefaultIdentifier($identifier);
        return \sprintf('INSERT INTO %s (%s) VALUES (%s)', $identifier->escape($this->table), \implode(', ', $identifier->all($this->columns)), $this->stringifyIterator($this->generatePlaceholders()));
    }
    // Statement
    public function params()
    {
        return $this->placeholderParams();
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
     * @var array
     */
    protected $params = [];
    /**
     * Generate a placeholder.
     */
    protected function generatePlaceholders()
    {
        foreach (\array_keys($this->params) as $index) {
            (yield $this->placeholderValue($index));
        }
    }
}