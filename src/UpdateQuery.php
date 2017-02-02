<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Iterator;

class UpdateQuery implements Statement
{
    use Traits\CanConvertIteratorToString;
    use Traits\CanCreatePlaceholders;
    use Traits\CanReplaceBooleanAndNullValues;
    use Traits\CanUseDefaultIdentifier;

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
    public function sql(Identifier $identifier = null): string
    {
        if (!$this->where) {
            throw QueryBuilderException::updateRequiresWhere();
        }

        $identifier = $this->getDefaultIdentifier($identifier);

        return \sprintf(
            'UPDATE %s SET %s WHERE %s',
            $identifier->escapeQualified($this->table),
            $this->stringifyIterator($this->generateSetList($identifier)),
            $this->where->sql($identifier)
        );
    }

    // Statement
    public function params(): array
    {
        return \array_merge($this->placeholderParams(), $this->where->params());
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
     * Generate a column and placeholder pair.
     */
    protected function generateSetList(Identifier $identifier): Iterator
    {
        foreach ($this->columns as $idx => $column) {
            yield $identifier->escape($column) . ' = ' . $this->placeholderValue($idx);
        }
    }
}
