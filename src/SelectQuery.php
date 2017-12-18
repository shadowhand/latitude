<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Iterator;

/**
 * Class SelectQuery
 * @package Latitude\QueryBuilder
 */
class SelectQuery implements Query
{
    use Traits\CanConvertIteratorToString;
    use Traits\CanLimit;
    use Traits\CanOrderBy;
    use Traits\CanUseDefaultIdentifier;

    /**
     * @param array ...$columns
     * @return SelectQuery
     */
    public static function make(...$columns): SelectQuery
    {
        $query = new static();
        if ($columns) {
            $query->columns(...$columns);
        }
        return $query;
    }

    /**
     * @param bool $distinct
     * @return self
     */
    public function distinct(bool $distinct = true): self
    {
        $this->distinct = $distinct;
        return $this;
    }

    /**
     * @param array ...$columns
     * @return self
     */
    public function columns(...$columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @param array<int, string> $tables
     * @return self
     */
    public function from(string ...$tables): self
    {
        $this->from = $tables;
        return $this;
    }

    /**
     * @param string|Statement $table
     * @param Conditions $conditions
     * @param string $type
     * @return self
     * @throws \TypeError
     */
    public function join($table, Conditions $conditions, string $type = ''): self
    {
        $this->join[] = [\strtoupper($type), reference($table), $conditions];
        return $this;
    }

    /**
     * @param $table
     * @param Conditions $conditions
     * @return self
     * @throws \TypeError
     */
    public function innerJoin($table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'INNER');
    }

    /**
     * @param string|Statement $table
     * @param Conditions $conditions
     * @return self
     * @throws \TypeError
     */
    public function outerJoin($table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'OUTER');
    }

    /**
     * @param string|Statement $table
     * @param Conditions $conditions
     * @return self
     * @throws \TypeError
     */
    public function leftJoin($table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'LEFT');
    }

    /**
     * @param string|Statement $table
     * @param Conditions $conditions
     * @return self
     * @throws \TypeError
     */
    public function leftOuterJoin($table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'LEFT OUTER');
    }

    /**
     * @param string|Statement $table
     * @param Conditions $conditions
     * @return self
     * @throws \TypeError
     */
    public function rightJoin($table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'RIGHT');
    }

    /**
     * @param string|Statement $table
     * @param Conditions $conditions
     * @return self
     * @throws \TypeError
     */
    public function rightOuterJoin($table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'RIGHT OUTER');
    }

    /**
     * @param string|Statement $table
     * @param Conditions $conditions
     * @return self
     * @throws \TypeError
     */
    public function fullJoin($table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'FULL');
    }

    /**
     * @param string|Statement $table
     * @param Conditions $conditions
     * @return self
     * @throws \TypeError
     */
    public function fullOuterJoin($table, Conditions $conditions): self
    {
        return $this->join($table, $conditions, 'FULL OUTER');
    }

    /**
     * @param Conditions $where
     * @return self
     */
    public function where(Conditions $where): self
    {
        $this->where = $where;
        return $this;
    }

    /**
     * @param array ...$columns
     * @return self
     */
    public function groupBy(...$columns): self
    {
        $this->groupBy = $columns;
        return $this;
    }

    /**
     * @param Conditions $having
     * @return self
     */
    public function having(Conditions $having): self
    {
        $this->having = $having;
        return $this;
    }

    /**
     * @param int|null $offset
     * @return self
     */
    public function offset(int $offset = null): self
    {
        $this->offset = $offset;
        return $this;
    }

    // Statement

    /**
     * @param Identifier|null $identifier
     * @return string
     */
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
    /**
     * @return array
     */
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
    protected $where = null;

    /**
     * @var array
     */
    protected $groupBy = [];

    /**
     * @var Conditions
     */
    protected $having = null;

    /**
     * @var int|null
     */
    protected $offset = null;

    /**
     * Generate a list of JOIN statements.
     *
     * @param Identifier $identifier
     * @return Iterator
     */
    protected function generateJoins(Identifier $identifier): Iterator
    {
        foreach ($this->join as $join) {
            yield \trim(sprintf(
                '%s JOIN %s ON %s',
                $join[0],
                $join[1]->sql($identifier),
                $join[2]->sql($identifier)
            ));
        }
    }

    /**
     * Get a flattened array of join parameters.
     *
     * @return array
     */
    protected function joinParams(): array
    {
        $params = [];
        foreach ($this->join as $join) {
            $params[] = $join[1]->params();
            $params[] = $join[2]->params();
        }

        // flatten: [[a, b], [c, ...]] -> [a, b, c]
        return \array_merge(...$params);
    }
}
