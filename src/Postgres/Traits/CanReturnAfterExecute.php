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
        $identifier = $this->getDefaultIdentifier($identifier);
        return \sprintf('%s RETURNING %s', parent::sql($identifier), \implode(', ', $identifier->allAliases($this->returning)));
    }
    /**
     * @var array
     */
    protected $returning = [];
}