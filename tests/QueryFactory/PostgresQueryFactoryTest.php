<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Query\Postgres\InsertQuery;
use Latitude\QueryBuilder\Query\Postgres\UpdateQuery;
use PHPUnit\Framework\TestCase;

class PostgresQueryFactoryTest extends TestCase
{
    private PostgresQueryFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new PostgresQueryFactory();
    }

    public function testInsertQueryInstance(): void
    {
        $insertQuery = $this->factory->insert('users', ['user' => 'james']);
        $this->assertInstanceOf(InsertQuery::class, $insertQuery);
    }

    public function testUpdateQueryInstance(): void
    {
        $updateQuery = $this->factory->update('users', ['foo' => 'bar']);
        $this->assertInstanceOf(UpdateQuery::class, $updateQuery);
    }
}
