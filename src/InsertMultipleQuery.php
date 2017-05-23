<?php

namespace Latitude\QueryBuilder;

class InsertMultipleQuery implements Statement
{
    use Traits\CanUseDefaultIdentifier;
    /**
     * Create a new multi-line insert query.
     */
    public static function make($table, array $columns = [])
    {
        $query = new static();
        $query->table($table);
        if ($columns) {
            $query->columns($columns);
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
    public function columns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }
    /**
     * Append values to insert.
     */
    public function append(array $values)
    {
        $this->values[] = ValueList::make(...$values);
        return $this;
    }
    // Statement
    public function sql(Identifier $identifier = null)
    {
        $identifier = $this->getDefaultIdentifier($identifier);
        return \sprintf('INSERT INTO %s (%s) VALUES ' . $this->insertLines(), $identifier->escape($this->table), \implode(', ', $identifier->all($this->columns)));
    }
    // Statement
    public function params()
    {
        $paramsOf = static function (ValueList $values) {
            return $values->params();
        };
        // [[a], [b], [c]] -> [a, b, c]
        return \array_merge(...\array_map($paramsOf, $this->values));
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
     * Create a list of insert lines.
     */
    protected function insertLines()
    {
        $sqlOf = static function (ValueList $values) {
            return $values->sql();
        };
        return implode(', ', \array_map($sqlOf, $this->values));
    }
}