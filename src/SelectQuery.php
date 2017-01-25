<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Iterator;

class SelectQuery implements Statement
{
    use Traits\CanEscapeIdentifiers;

    public static function make(string ...$columns): SelectQuery
    {
        $query = new static();
        if ($columns) {
            $query->columns(...$columns);
        }
        return $query;
    }

    public function columns(string ...$columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function from(string ...$tables): self
    {
        $this->from = $tables;
        return $this;
    }

    public function join(string $table, Conditions $conditions, string $bind = 'ON', string $type = ''): self
    {
        $this->join[] = [\strtoupper($type), $table, \strtoupper($bind), $conditions];
        return $this;
    }

    public function innerJoin($table, Conditions $conditions, string $bind = 'ON'): self
    {
        return $this->join($table, $conditions, $bind, 'INNER');
    }

    public function outerJoin($table, Conditions $conditions, string $bind = 'ON'): self
    {
        return $this->join($table, $conditions, $bind, 'OUTER');
    }

    public function leftJoin($table, Conditions $conditions, string $bind = 'ON'): self
    {
        return $this->join($table, $conditions, $bind, 'LEFT');
    }

    public function leftOuterJoin($table, Conditions $conditions, string $bind = 'ON'): self
    {
        return $this->join($table, $conditions, $bind, 'LEFT OUTER');
    }

    public function rightJoin($table, Conditions $conditions, string $bind = 'ON'): self
    {
        return $this->join($table, $conditions, $bind, 'RIGHT');
    }

    public function rightOuterJoin($table, Conditions $conditions, string $bind = 'ON'): self
    {
        return $this->join($table, $conditions, $bind, 'RIGHT OUTER');
    }

    public function fullJoin($table, Conditions $conditions, string $bind = 'ON'): self
    {
        return $this->join($table, $conditions, $bind, 'FULL');
    }

    public function fullOuterJoin($table, Conditions $conditions, string $bind = 'ON'): self
    {
        return $this->join($table, $conditions, $bind, 'FULL OUTER');
    }

    public function where(Conditions $where): self
    {
        $this->where = $where;
        return $this;
    }

    public function groupBy(string ...$tables): self
    {
        $this->groupBy = $tables;
        return $this;
    }

    public function having(Conditions $having): self
    {
        $this->having = $having;
        return $this;
    }

    public function orderBy(array ...$sorting): self
    {
        $this->orderBy = $sorting;
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    // Statement
    public function sql(): string
    {
        // SELECT ...
        $parts = ['SELECT'];
        if (empty($this->columns)) {
            $parts[] = '*';
        } else {
            $parts[] = $this->escapeIdentifiers($this->columns);
        }

        // FROM ...
        $parts[] = 'FROM';
        $parts[] = $this->escapeIdentifiers($this->from);

        // JOIN ...
        if (\count($this->join)) {
            \array_map(
                function (array $join) use (&$parts) {
                    list($type, $table, $bind, $condition) = $join;
                    $parts[] = \trim("$type JOIN");
                    $parts[] = $this->escapeIdentifier($table);
                    $parts[] = $bind;
                    $parts[] = $condition->sql();
                },
                $this->join
            );
        }

        // WHERE ...
        if ($this->where) {
            $parts[] = 'WHERE';
            $parts[] = $this->where->sql();
        }

        // GROUP BY ...
        if ($this->groupBy) {
            $parts[] = 'GROUP BY';
            $parts[] = $this->escapeIdentifiers($this->groupBy);
        }

        // HAVING ...
        if ($this->having) {
            $parts[] = 'HAVING';
            $parts[] = $this->having->sql();
        }

        // ORDER BY ...
        if ($this->orderBy) {
            $parts[] = 'ORDER BY';
            $parts[] = $this->escapeOrderBy($this->orderBy);
        }

        // LIMIT ...
        if (isset($this->limit)) {
            $parts[] = 'LIMIT';
            $parts[] = $this->limit;
        }

        // OFFSET ...
        if (isset($this->offset)) {
            $parts[] = 'OFFSET';
            $parts[] = $this->offset;
        }

        return \implode(' ', $parts);
    }

    // Statement
    public function params(): array
    {
        $params = [];
        // TODO: JOIN params?
        if ($this->where) {
            $params = \array_merge($params, $this->where->params());
        }
        if ($this->having) {
            $params = \array_merge($params, $this->having->params());
        }
        return $params;
    }

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $from = [];

    /**
     * @var array
     */
    protected $join = [];

    /**
     * @var Conditions
     */
    protected $where;

    /**
     * @var array
     */
    protected $groupBy;

    /**
     * @var Conditions
     */
    protected $having;

    /**
     * @var array
     */
    protected $orderBy;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * Create a list of ORDER BY statements.
     */
    protected function escapeOrderBy(): string
    {
        return \implode(', ', \iterator_to_array($this->generateOrderBy()));
    }

    /**
     * Generate a list of ORDER BY statements.
     */
    protected function generateOrderBy(): Iterator
    {
        foreach ($this->orderBy as $sort) {
            if (empty($sort[1])) {
                yield $this->escapeIdentifier($sort[0]);
            } else {
                yield $this->escapeIdentifier($sort[0]) . ' ' . \strtoupper($sort[1]);
            }
        }
    }
}
