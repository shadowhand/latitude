<?php

namespace Latitude\QueryBuilder;

class Conditions implements Statement
{
    use Traits\CanUseDefaultIdentifier;
    /**
     * Create a new conditions instance.
     */
    public static function make($condition = null, ...$params)
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
    public function with($condition, ...$params)
    {
        return $this->andWith($condition, ...$params);
    }
    /**
     * Add a condition that will be applied with a logical "AND".
     */
    public function andWith($condition, ...$params)
    {
        return $this->addCondition('AND', $condition, $params);
    }
    /**
     * Add a condition that will be applied with a logical "OR".
     */
    public function orWith($condition, ...$params)
    {
        return $this->addCondition('OR', $condition, $params);
    }
    /**
     * Alias for andGroup().
     */
    public function group()
    {
        return $this->andGroup();
    }
    /**
     * Start a new grouping that will be applied with a logical "AND".
     *
     * Exit the group with end().
     */
    public function andGroup()
    {
        return $this->addConditionGroup('AND');
    }
    /**
     * Start a new grouping that will be applied with a logical "OR".
     *
     * Exit the group with end().
     */
    public function orGroup()
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
    public function end()
    {
        return $this->parent ?: $this;
    }
    // Statement
    public function sql(Identifier $identifier = null)
    {
        $identifier = $this->getDefaultIdentifier($identifier);
        $expression = \array_reduce($this->parts, $this->sqlReducer(), '');
        return $identifier->escapeExpression($expression);
    }
    // Statement
    public function params()
    {
        return \array_reduce($this->parts, $this->paramReducer(), []);
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
    protected function addCondition($type, $condition, array $params)
    {
        $this->parts[] = compact('type', 'condition', 'params');
        return $this;
    }
    /**
     * Add a condition group to the current conditions.
     */
    protected function addConditionGroup($type)
    {
        $condition = new static($this);
        $this->parts[] = compact('type', 'condition');
        return $condition;
    }
    /**
     * Get a function to reduce condition parts to a SQL string.
     */
    protected function sqlReducer()
    {
        return function ($sql, array $part) {
            if ($this->isCondition($part['condition'])) {
                // (...)
                $statement = "({$part['condition']->sql()})";
            } else {
                // foo = ?
                $statement = $this->replaceStatementParams($part['condition'], $part['params']);
            }
            if ($sql) {
                // AND ...
                $statement = "{$part['type']} {$statement}";
            }
            return \trim($sql . ' ' . $statement);
        };
    }
    /**
     * Get a function to reduce parameters to a single list.
     */
    protected function paramReducer()
    {
        return function (array $params, array $part) {
            if ($this->isCondition($part['condition'])) {
                // Conditions have a parameter list already
                return \array_merge($params, $part['condition']->params());
            }
            // Otherwise convert the list to a list of lists for flattening
            return \array_merge($params, ...\array_map($this->paramLister(), $part['params']));
        };
    }
    /**
     * Convert all parameters to an array for flattening.
     */
    protected function paramLister()
    {
        return function ($param) {
            if ($this->isStatement($param)) {
                // Statements have a parameter list already
                return $param->params();
            }
            // Otherwise convert to a list
            return [$param];
        };
    }
    /**
     * Check if a condition is a sub-condition.
     */
    protected function isCondition($condition)
    {
        if (\is_object($condition) === false) {
            return false;
        }
        return $condition instanceof Conditions;
    }
    /**
     * Check if a parameter is a statement.
     */
    protected function isStatement($param)
    {
        if (\is_object($param) === false) {
            return false;
        }
        return $param instanceof Statement;
    }
    /**
     * Check if any parameter is a statement.
     */
    protected function hasStatementParam(array $params)
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
    protected function replaceStatementParams($statement, array $params)
    {
        if ($this->hasStatementParam($params) === false) {
            return $statement;
        }
        // Maintain an offset position, as preg_replace_callback() does not provide one
        $index = 0;
        return \preg_replace_callback('/\\?/', function ($matches) use(&$index, $params) {
            try {
                if ($this->isStatement($params[$index])) {
                    // Replace any statement placeholder with the generated SQL
                    return $params[$index]->sql();
                } else {
                    // And leave all other placeholders intact
                    return $matches[0];
                }
            } finally {
                // This funky usage of finally allows us to increment the offset
                // after all other code in the block has been executed.
                $index++;
            }
        }, $statement);
    }
}