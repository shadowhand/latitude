<?php

namespace Latitude\QueryBuilder;

use Iterator;
class DeleteQuery implements Statement
{
    use Traits\CanCreatePlaceholders;
    use Traits\CanUseDefaultIdentifier;
    /**
     * Create a new update query.
     */
    public static function make($table)
    {
        $query = new static();
        $query->table($table);
        return $query;
    }
    /**
     * Set the table to update.
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }
    /**
     * Set the conditions for the update.
     */
    public function where(Conditions $where)
    {
        $this->where = $where;
        return $this;
    }
    // Statement
    public function sql(Identifier $identifier = null)
    {
        if (!$this->where) {
            throw QueryBuilderException::deleteRequiresWhere();
        }
        $identifier = $this->getDefaultIdentifier($identifier);
        return \sprintf('DELETE FROM %s WHERE %s', $identifier->escapeQualified($this->table), $this->where->sql($identifier));
    }
    // Statement
    public function params()
    {
        return $this->where->params();
    }
    /**
     * @var string
     */
    protected $table;
    /**
     * @var Conditions
     */
    protected $where;
}