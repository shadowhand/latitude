<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Postgres\Traits;

trait CanReturnAfterExecute
{
    /**
     * Set the columns to return after insert.
     */
    public function returning(array $columns): self
    {
        $this->returning = $columns;
        return $this;
    }

    // Statement
    public function sql(): string
    {
        $query = parent::sql();

        return \sprintf(
            '%s RETURNING %s',
            parent::sql(),
            $this->escapeIdentifiers($this->returning)
        );
    }

    /**
     * @var array
     */
    protected $returning = [];
}
