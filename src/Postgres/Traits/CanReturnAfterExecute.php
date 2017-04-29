<?php

namespace Latitude\QueryBuilder\Postgres\Traits;

use Latitude\QueryBuilder\Identifier;
use Latitude\QueryBuilder\Traits\CanUseDefaultIdentifier;
trait CanReturnAfterExecute
{
    use CanUseDefaultIdentifier;
    /**
     * Set the columns to return after insert.
     */
    public function returning(array $columns)
    {
        $this->returning = $columns;
        return $this;
    }
    // Statement
    public function sql(Identifier $identifier = null)
    {
        $sql = parent::sql($identifier);
        if (empty($this->returning)) {
            return $sql;
        }
        $identifier = $this->getDefaultIdentifier($identifier);
        return \sprintf('%s RETURNING %s', $sql, \implode(', ', $identifier->allAliases($this->returning)));
    }
    /**
     * @var array
     */
    protected $returning = [];
}