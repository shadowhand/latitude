<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

/**
 * Class QueryFactory
 * @package Latitude\QueryBuilder
 */
class QueryFactory
{
    const ENGINES = [
        'mysql' => 'MySQL',
        'pgsql' => 'Postgres',
        'sqlsrv' => 'SqlServer',
    ];

    /**
     * QueryFactory constructor.
     *
     * @param string|null $engine
     * @param bool $setIdentifier
     */
    public function __construct(string $engine = null, bool $setIdentifier = true)
    {
        if ($engine && \array_key_exists($engine, static::ENGINES)) {
            $this->engine = static::ENGINES[$engine];
        } else {
            $this->engine = 'Common';
        }

        if ($setIdentifier) {
            $this->setDefaultIdentifier();
        }
    }

    /**
     * Create a new SELECT query.
     *
     * @param array ...$params
     * @return SelectQuery
     */
    public function select(...$params): SelectQuery
    {
        return SelectQuery::make(...$params);
    }

    /**
     * Create a new INSERT query.
     *
     * @param array ...$params
     * @return InsertQuery
     */
    public function insert(...$params): InsertQuery
    {
        if ($this->isPostgres()) {
            return Postgres\InsertQuery::make(...$params);
        }

        return InsertQuery::make(...$params);
    }

    /**
     * Create a new UPDATE query.
     *
     * @param array ...$params
     * @return UpdateQuery
     */
    public function update(...$params): UpdateQuery
    {
        if ($this->isMySQL()) {
            return MySQL\UpdateQuery::make(...$params);
        }

        if ($this->isPostgres()) {
            return Postgres\UpdateQuery::make(...$params);
        }

        return UpdateQuery::make(...$params);
    }

    /**
     * Create a new DELETE query.
     *
     * @param array ...$params
     * @return DeleteQuery
     */
    public function delete(...$params): DeleteQuery
    {
        if ($this->isMySQL()) {
            return MySQL\DeleteQuery::make(...$params);
        }

        if ($this->isPostgres()) {
            return Postgres\DeleteQuery::make(...$params);
        }

        return DeleteQuery::make(...$params);
    }

    /**
     * Create an identifier instance.
     *
     * @return Identifier
     */
    public function identifier(): Identifier
    {
        if ($this->isMySQL()) {
            return MySQL\Identifier::make();
        }

        if ($this->isSqlServer()) {
            return SqlServer\Identifier::make();
        }

        return Common\Identifier::make();
    }

    /**
     * Is the engine MySQL?
     *
     * @return bool
     */
    public function isMySQL(): bool
    {
        return $this->engine === 'MySQL';
    }

    /**
     * Is the engine Postgres?
     *
     * @return bool
     */
    public function isPostgres(): bool
    {
        return $this->engine === 'Postgres';
    }

    /**
     * Is the engine SQL Server?
     *
     * @return bool
     */
    public function isSqlServer(): bool
    {
        return $this->engine === 'SqlServer';
    }

    /**
     * @var string
     */
    protected $engine = '';

    /**
     * Set the default identifier for the engine.
     * @return void
     */
    protected function setDefaultIdentifier()
    {
        Identifier::setDefault($this->identifier());
    }
}
