<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Iterator;

class SelectQuery implements Statement
{
    use Traits\CanConvertIteratorToString;
    use Traits\CanLimit;
    use Traits\CanOrderBy;
    use Traits\CanUseDefaultIdentifier;

    public static function make(...$columns): SelectQuery
    {
        $query = new static();
        if ($columns) {
            $query->columns(...$columns);
        }
        return $query;
    }

    public function distinct(bool $distinct = true): self
    {
        $this->distinct = $distinct;
        return $this;
    }

    public function columns(...$columns): self
    {
        $this->columns = $columns ?: null;
        return $this;
    }

    public function from(string ...$tables): self
    {
        $this->from = $tables;
        return $this;
    }

    public function join(string $table, Conditions $conditions, string $type = ''): self
    {
        $this->join[] = [\strtoupper($type), $table, $conditions];
        return $this;
    }

    public function innerJoin(string $table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'INNER');
    }

    public function outerJoin(string $table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'OUTER');
    }

    public function leftJoin(string $table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'LEFT');
    }

    public function leftOuterJoin(string $table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'LEFT OUTER');
    }

    public function rightJoin(string $table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'RIGHT');
    }

    public function rightOuterJoin(string $table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'RIGHT OUTER');
    }

    public function fullJoin(string $table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'FULL');
    }

    public function fullOuterJoin(string $table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'FULL OUTER');
    }

    public function resetJoins()
    {
        $this->join = [];
        return $this;
    }

    public function where(Conditions $where = null): self
    {
        $this->where = $where;
        return $this;
    }

    public function groupBy(...$columns): self
    {
        $this->groupBy = $columns ?: null;
        return $this;
    }

    public function having(Conditions $having = null): self
    {
        $this->having = $having;
        return $this;
    }

    public function offset(int $offset = null): self
    {
        $this->offset = $offset;
        return $this;
    }

    // Statement
    public function sql(Identifier $identifier = null): string
    {
        $identifier = $this->getDefaultIdentifier($identifier);

        // SELECT ...
        if ($this->distinct) {
            $parts = ['SELECT DISTINCT'];
        } else {
            $parts = ['SELECT'];
        }

        if ($this->columns) {
            $parts[] = \implode(', ', $identifier->allAliases($this->columns));
        } else {
            $parts[] = '*';
        }

        // FROM ...
        $parts[] = 'FROM';
        $parts[] = \implode(', ', $identifier->allAliases($this->from));

        // JOIN ...
        if (\count($this->join)) {
            $parts[] = $this->stringifyIterator($this->generateJoins($identifier), ' ');
        }

        // WHERE ...
        if ($this->where) {
            $parts[] = 'WHERE';
            $parts[] = $this->where->sql($identifier);
        }

        // GROUP BY ...
        if ($this->groupBy) {
            $parts[] = 'GROUP BY';
            $parts[] = \implode(', ', $identifier->allQualified($this->groupBy));
        }

        // HAVING ...
        if ($this->having) {
            $parts[] = 'HAVING';
            $parts[] = $this->having->sql($identifier);
        }

        // ORDER BY ...
        if (isset($this->orderBy)) {
            $parts[] = $this->orderByAsSql($identifier);
        }

        // LIMIT ...
        if (isset($this->limit)) {
            $parts[] = $this->limitAsSql();
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
        if ($this->join) {
            $params = \array_merge($params, $this->joinParams());
        }
        if ($this->where) {
            $params = \array_merge($params, $this->where->params());
        }
        if ($this->having) {
            $params = \array_merge($params, $this->having->params());
        }
        return $params;
    }

    /**
     * @var bool
     */
    protected $distinct = false;

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
     * @var int
     */
    protected $offset;

    /**
     * Generate a list of JOIN statements.
     */
    protected function generateJoins(Identifier $identifier): Iterator
    {
        foreach ($this->join as $join) {
            yield \trim(sprintf(
                '%s JOIN %s ON %s',
                $join[0],
                $identifier->escapeAlias($join[1]),
                $join[2]->sql($identifier)
            ));
        }
    }

    /**
     * Get a flattened array of join parameters.
     */
    protected function joinParams(): array
    {
        $params = [];
        foreach ($this->join as $join) {
            $params[] = $join[2]->params();
        }

        // flatten: [[a, b], [c, ...]] -> [a, b, c]
        return \array_merge(...$params);
    }
}
