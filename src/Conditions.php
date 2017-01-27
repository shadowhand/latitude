<?php

namespace Latitude\QueryBuilder;

class Conditions implements Statement
{
    use Traits\CanCreatePlaceholders;
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
        $sql = \array_reduce($this->parts, function ($sql, array $part) {
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
        }, '');
        return $identifier->escapeExpression($sql);
    }
    // Statement
    public function params()
    {
        $reduce = function (array $params, array $part) {
            if ($this->isConditions($part['condition'])) {
                return \array_merge($params, $part['condition']->params());
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
     * Add a condition to the current conditions, expanding IN values.
     */
    protected function addCondition($type, $condition, array $params)
    {
        if ($params) {
            $this->expandPlaceholders($condition, $params);
        }
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
     * Replace placeholder with expanded placeholder for IN values.
     */
    protected function expandPlaceholders(&$condition, array &$params)
    {
        if ($params[0] instanceof InValue) {
            $placeholders = $this->createPlaceholders(\count($params[0]));
            $condition = \str_replace('?', "({$placeholders})", $condition);
            $params = $params[0]->values();
        }
    }
    /**
     * Check if a condition is a sub-condition.
     */
    protected function isConditions($condition)
    {
        if (\is_object($condition) === false) {
            return false;
        }
        return $condition instanceof Conditions;
    }
}