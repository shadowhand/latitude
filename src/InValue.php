<?php

namespace Latitude\QueryBuilder;

use Countable;
class InValue implements Countable
{
    /**
     * Create a new IN value wrapper.
     */
    public static function make(array $values)
    {
        return new static($values);
    }
    public function values()
    {
        return $this->values;
    }
    // Countable
    public function count()
    {
        return \count($this->values);
    }
    /**
     * @var array
     */
    protected $values;
    protected function __construct(array $values)
    {
        $this->values = $values;
    }
}