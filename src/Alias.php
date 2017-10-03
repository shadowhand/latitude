<?php

namespace Latitude\QueryBuilder;

class Alias implements Statement
{
    /**
     * Create a new alias.
     *
     * @param Statement|string $statement
     */
    public static function make($statement, $alias)
    {
        return new static(reference($statement), $alias);
    }
    // Statement
    public function sql(Identifier $identifier = null)
    {
        return sprintf(isQuery($this->statement) ? '(%s) AS %s' : '%s AS %s', $this->statement->sql($identifier), $this->alias);
    }
    // Statement
    public function params()
    {
        return $this->statement->params();
    }
    /**
     * @var Statement
     */
    protected $statement;
    /**
     * @var string
     */
    protected $alias;
    /**
     * @see Alias::make()
     */
    protected function __construct(Statement $statement, $alias)
    {
        $this->statement = $statement;
        $this->alias = $alias;
    }
}