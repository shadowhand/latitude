<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

class QueryFactory
{
    const ENGINES = [
        'mysql' => 'MySQL',
        'pgsql' => 'Postgres',
        'sqlsrv' => 'SqlServer',
    ];

    public function __construct(string $engine, bool $setIdentifier = true)
    {
        if (\array_key_exists($engine, static::ENGINES)) {
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
     */
    public function select(...$params): SelectQuery
    {
        return SelectQuery::make(...$params);
    }

    /**
     * Create a new INSERT query.
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
     */
    public function update(...$params): UpdateQuery
    {
        if ($this->isPostgres()) {
            return Postgres\UpdateQuery::make(...$params);
        }

        return UpdateQuery::make(...$params);
    }

    /**
     * Create a new DELETE query.
     */
    public function delete(...$params): DeleteQuery
    {
        if ($this->isPostgres()) {
            return Postgres\DeleteQuery::make(...$params);
        }

        return DeleteQuery::make(...$params);
    }

    /**
     * Create an identifier instance.
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
     */
    public function isMySQL(): bool
    {
        return $this->engine === 'MySQL';
    }

    /**
     * Is the engine Postgres?
     */
    public function isPostgres(): bool
    {
        return $this->engine === 'Postgres';
    }

    /**
     * Is the engine SQL Server?
     */
    public function isSqlServer(): bool
    {
        return $this->engine === 'SqlServer';
    }

    /**
     * @var string
     */
    protected $engine;

    /**
     * Set the default identifier for the engine.
     */
    protected function setDefaultIdentifier()
    {
        Identifier::setDefault($this->identifier());
    }
}
