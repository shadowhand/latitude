<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Iterator;

class DeleteQuery implements Statement
{
    use Traits\CanCreatePlaceholders;
    use Traits\CanEscapeIdentifiers;

    /**
     * Create a new update query.
     */
    public static function make(string $table): DeleteQuery
    {
        $query = new static();
        $query->table($table);
        return $query;
    }

    /**
     * Set the table to update.
     */
    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set the conditions for the update.
     */
    public function where(Conditions $where): self
    {
        $this->where = $where;
        return $this;
    }

    // Statement
    public function sql(): string
    {
        if (!$this->where) {
            throw QueryBuilderException::deleteRequiresWhere();
        }

        return \sprintf(
            'DELETE FROM %s WHERE %s',
            $this->escapeIdentifier($this->table),
            $this->where->sql()
        );
    }

    // Statement
    public function params(): array
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
