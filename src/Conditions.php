<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

class Conditions implements Statement
{
    use Traits\CanUseDefaultIdentifier;

    /**
     * Create a new conditions instance.
     */
    public static function make(string $condition = null, ...$params): Conditions
    {
        $statment = new static();
        if ($condition) {
            $statment->with($condition, ...$params);
        }
        return $statment;
    }

    /**
     * Alias of andWith().
     */
    public function with(string $condition, ...$params): self
    {
        return $this->andWith($condition, ...$params);
    }

    /**
     * Add a condition that will be applied with a logical "AND".
     */
    public function andWith(string $condition, ...$params): self
    {
        return $this->addCondition('AND', $condition, $params);
    }

    /**
     * Add a condition that will be applied with a logical "OR".
     */
    public function orWith(string $condition, ...$params): self
    {
        return $this->addCondition('OR', $condition, $params);
    }

    /**
     * Alias for andGroup().
     */
    public function group(): Conditions
    {
        return $this->andGroup();
    }

    /**
     * Start a new grouping that will be applied with a logical "AND".
     *
     * Exit the group with end().
     */
    public function andGroup(): Conditions
    {
        return $this->addConditionGroup('AND');
    }

    /**
     * Start a new grouping that will be applied with a logical "OR".
     *
     * Exit the group with end().
     */
    public function orGroup(): Conditions
    {
        return $this->addConditionGroup('OR');
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
    public function sql(Identifier $identifier = null): string
    {
        $identifier = $this->getDefaultIdentifier($identifier);

        $sql = \array_reduce(
            $this->parts,
            function (string $sql, array $part): string {
                if ($this->isConditions($part['condition'])) {
                    // (...)
                    $statement = '(' . $part['condition']->sql() . ')';
                } else {
                    // foo = ?
                    $statement = $this->replaceStatementParams($part['condition'], $part['params']);
                }

                if ($sql) {
                    $statement = $part['type'] . ' ' . $statement;
                }

                return \trim($sql . ' ' . $statement);
            },
            ''
        );

        return $identifier->escapeExpression($sql);
    }

    // Statement
    public function params(): array
    {
        $params = [];
        foreach ($this->parts as $part) {
            if ($this->isConditions($part['condition'])) {
                $params = \array_merge($params, $part['condition']->params());
            } else {
                foreach ($part['params'] as $param) {
                    if ($this->isStatement($param)) {
                        $params = \array_merge($params, $param->params());
                    } else {
                        $params[] = $param;
                    }
                }
            }
        }

        return $params;
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
     * Add a condition to the current conditions, expanding IN values.
     */
    protected function addCondition(string $type, string $condition, array $params): self
    {
        $this->parts[] = compact('type', 'condition', 'params');
        return $this;
    }

    /**
     * Add a condition group to the current conditions.
     */
    protected function addConditionGroup(string $type): Conditions
    {
        $condition = new static($this);
        $this->parts[] = compact('type', 'condition');
        return $condition;
    }

    /**
     * Check if a condition is a sub-condition.
     */
    protected function isConditions($condition): bool
    {
        if (\is_object($condition) === false) {
            return false;
        }

        return $condition instanceof Conditions;
    }

    /**
     * Check if a parameter is a statement.
     */
    protected function isStatement($param): bool
    {
        if (\is_object($param) === false) {
            return false;
        }

        return $param instanceof Statement;
    }

    /**
     * Check if any parameter is a statement.
     */
    protected function hasStatementParam(array $params): bool
    {
        foreach ($params as $param) {
            if ($this->isStatement($param)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Replacement statement parameters with SQL expression.
     */
    protected function replaceStatementParams(string $statement, array $params): string
    {
        if ($this->hasStatementParam($params) === false) {
            return $statement;
        }

        $index = 0;
        return \preg_replace_callback('/\?/', function ($matches) use (&$index, $params) {
            try {
                // Replace any statement placeholder with the generated SQL
                if ($this->isStatement($params[$index])) {
                    return $params[$index]->sql();
                } else {
                    return $matches[0];
                }
            } finally {
                $index++;
            }
        }, $statement);
    }
}
