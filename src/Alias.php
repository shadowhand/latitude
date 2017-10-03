<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

class Alias implements Statement
{
    /**
     * Create a new alias.
     *
     * @param Statement|string $statement
     */
    public static function make($statement, string $alias): Alias
    {
        return new static(reference($statement), $alias);
    }

    // Statement
    public function sql(Identifier $identifier = null): string
    {
        return sprintf(
            isQuery($this->statement) ? '(%s) AS %s' : '%s AS %s',
            $this->statement->sql($identifier),
            $this->alias
        );
    }

    // Statement
    public function params(): array
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
    protected function __construct(Statement $statement, string $alias)
    {
        $this->statement = $statement;
        $this->alias = $alias;
    }
}
