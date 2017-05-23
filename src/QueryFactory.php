<?php

namespace Latitude\QueryBuilder;

class QueryFactory
{
    const ENGINES = ['mysql' => 'MySQL', 'pgsql' => 'Postgres', 'sqlsrv' => 'SqlServer'];
    public function __construct($engine = null, $setIdentifier = true)
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
     */
    public function select(...$params)
    {
        return SelectQuery::make(...$params);
    }
    /**
     * Create a new INSERT query.
     */
    public function insert(...$params)
    {
        if ($this->isPostgres()) {
            return Postgres\InsertQuery::make(...$params);
        }
        return InsertQuery::make(...$params);
    }
    /**
     * Create a new INSERT query with multiple values.
     */
    public function insertMultiple(...$params)
    {
        return InsertMultipleQuery::make(...$params);
    }
    /**
     * Create a new UPDATE query.
     */
    public function update(...$params)
    {
        if ($this->isPostgres()) {
            return Postgres\UpdateQuery::make(...$params);
        }
        return UpdateQuery::make(...$params);
    }
    /**
     * Create a new DELETE query.
     */
    public function delete(...$params)
    {
        if ($this->isPostgres()) {
            return Postgres\DeleteQuery::make(...$params);
        }
        return DeleteQuery::make(...$params);
    }
    /**
     * Create an identifier instance.
     */
    public function identifier()
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
    public function isMySQL()
    {
        return $this->engine === 'MySQL';
    }
    /**
     * Is the engine Postgres?
     */
    public function isPostgres()
    {
        return $this->engine === 'Postgres';
    }
    /**
     * Is the engine SQL Server?
     */
    public function isSqlServer()
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