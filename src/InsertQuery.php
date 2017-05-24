<?php
declare(strict_types=1);

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
    public static function make(string $table, array $map = []): InsertQuery
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
    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set the columns to insert.
     */
    public function columns(string ...$columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Append values to insert.
     */
    public function values(...$values): self
    {
        if (\count($values) !== \count($this->columns)) {
            throw new InvalidArgumentException(sprintf(
                'Number of values (%d) does not match number of columns (%d)',
                \count($values),
                \count($this->columns)
            ));
        }
        $this->values[] = ValueList::make(...$values);
        return $this;
    }

    /**
     * Set the columns and values to insert.
     *
     * NOTE: Existing values will be replaced!
     */
    public function map(array $map): self
    {
        $this->values = [];
        $this->columns(...\array_keys($map));
        $this->values(...\array_values($map));
        return $this;
    }

    // Statement
    public function sql(Identifier $identifier = null): string
    {
        $identifier = $this->getDefaultIdentifier($identifier);

        return \sprintf(
            'INSERT INTO %s (%s) VALUES %s',
            $identifier->escape($this->table),
            \implode(', ', $identifier->all($this->columns)),
            $this->stringifyIterator($this->insertLines())
        );
    }

    // Statement
    public function params(): array
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
    protected function insertLines(): Iterator
    {
        foreach ($this->values as $line) {
            yield "({$line->sql()})";
        }
    }

    /**
     * Convert all parameters to an array for flattening.
     */
    protected function paramLister(): callable
    {
        return function (ValueList $values): array {
            return $values->params();
        };
    }
}
