<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

class Conditions implements Statement
{
    /**
     * Create a new conditions instance.
     */
    public static function make(string $condition = null, ...$params): Conditions
    {
        $statment = new static();
        if ($condition) {
            $statment->where($condition, ...$params);
        }
        return $statment;
    }

    /**
     * Alias of logicalAnd().
     */
    public function where(string $condition, ...$params): self
    {
        return $this->logicalAnd($condition, ...$params);
    }

    /**
     * Add a condition that will be applied with a logical "AND".
     */
    public function logicalAnd(string $condition, ...$params): self
    {
        $this->parts[] = [
            'type' => 'AND',
            'condition' => $condition,
            'params' => $params,
        ];

        return $this;
    }

    /**
     * Add a condition that will be applied with a logical "OR".
     */
    public function logicalOr(string $condition, ...$params): self
    {
        $this->parts[] = [
            'type' => 'OR',
            'condition' => $condition,
            'params' => $params,
        ];

        return $this;
    }

    /**
     * Alias for andIn().
     */
    public function in(string $condition, array $params): self
    {
        return $this->andIn($condition, $params);
    }

    /**
     * Add an IN condition that will be applied with a logical "AND".
     *
     * Instead of using ? to denote the placeholder, ?* must be used!
     */
    public function andIn(string $condition, array $params): self
    {
        return $this->logicalAnd($this->unpackCondition($condition, \count($params)), ...$params);
    }

    /**
     * Add an IN condition that will be applied with a logical "OR".
     *
     * Instead of using "?" to denote the placeholder, "?*" must be used!
     */
    public function orIn(string $condition, array $params): self
    {
        return $this->logicalOr($this->unpackCondition($condition, \count($params)), ...$params);
    }

    /**
     * Alias for andGroup().
     */
    public function group(): self
    {
        return $this->andGroup();
    }

    /**
     * Start a new grouping that will be applied with a logical "AND".
     *
     * Exit the group with end().
     */
    public function andGroup(): self
    {
        $group = new static($this);

        $this->parts[] = [
            'type' => 'AND',
            'condition' => $group,
        ];

        return $group;
    }

    /**
     * Start a new grouping that will be applied with a logical "OR".
     *
     * Exit the group with end().
     */
    public function orGroup(): self
    {
        $group = new static($this);

        $this->parts[] = [
            'type' => 'OR',
            'condition' => $group,
        ];

        return $group;
    }

    /**
     * Exit the current grouping and return the parent statement.
     *
     * If no parent exists, the current conditions will be returned.
     *
     * @return Conditions
     */
    public function end(): Conditions
    {
        return $this->parent ?: $this;
    }

    // Statement
    public function sql(): string
    {
        return \array_reduce(
            $this->parts,
            function (string $sql, array $part): string {
                if ($this->isConditions($part['condition'])) {
                    // (...)
                    $statement = '(' . $part['condition']->sql() . ')';
                } else {
                    // foo = ?
                    $statement = $part['condition'];
                }

                if ($sql) {
                    $statement = $part['type'] . ' ' . $statement;
                }

                return \trim($sql . ' ' . $statement);
            },
            ''
        );
    }

    // Statement
    public function params(): array
    {
        $reduce = function (array $params, array $part): array {
            if ($this->isConditions($part['condition'])) {
                return \array_merge(
                    $params,
                    $part['condition']->params()
                );
            }
            return \array_merge($params, $part['params']);
        };

        return \array_reduce($this->parts, $reduce, []);
    }

    /**
     * @var array
     */
    protected $parts = [];

    /**
     * @var Conditions
     */
    protected $parent;

    protected function __construct(Conditions $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * Check if a condition is a sub-condition.
     *
     * @param mixed $condition
     *
     * @return bool
     */
    protected function isConditions($condition): bool
    {
        if (\is_object($condition) === false) {
            return false;
        }

        return $condition instanceof Conditions;
    }

    /**
     * Replace a grouped placeholder with a list of placeholders.
     *
     * Given a count of 3, the placeholder ?* will become ?, ?, ?
     *
     * @param string $condition
     * @param integer $count
     *
     * @return string
     */
    private function unpackCondition(string $condition, int $count): string
    {
        // Replace a grouped placeholder with an matching count of placeholders.
        $params = '?' . \str_repeat(', ?', $count - 1);
        return \str_replace('?*', $params, $condition);
    }
}
