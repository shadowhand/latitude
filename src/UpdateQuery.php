<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Iterator;

class UpdateQuery implements Statement
{
    use Traits\CanCreatePlaceholders;
    use Traits\CanEscapeIdentifiers;
    use Traits\CanReplaceBooleanAndNullValues;

    /**
     * Create a new update query.
     */
    public static function make(string $table, array $map): UpdateQuery
    {
        $query = new static();
        $query->table($table);
        if ($map) {
            $query->map($map);
        }
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
     * Set the columns and values to update.
     */
    public function map(array $map): self
    {
        $this->columns = \array_keys($map);
        $this->params = \array_values($map);
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
            throw QueryBuilderException::updateRequiresWhere();
        }

        return \sprintf(
            'UPDATE %s SET %s WHERE %s',
            $this->escapeIdentifier($this->table),
            $this->createSetList(),
            $this->where->sql()
        );
    }

    // Statement
    public function params(): array
    {
        return \array_merge($this->params, $this->where->params());
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
     * @var Conditions
     */
    protected $where;

    /**
     * Create a list of columns and placeholders.
     */
    protected function createSetList(): string
    {
        return \implode(', ', \iterator_to_array($this->generateSetList()));
    }

    /**
     * Generate a column and placeholder pair.
     */
    protected function generateSetList(): Iterator
    {
        foreach ($this->columns as $idx => $column) {
            yield $this->escapeIdentifier($column) . ' = ' . $this->placeholderValue($idx);
        }
    }
}
