<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Query\Sqlite\InsertQuery;
use Latitude\QueryBuilder\Query\Sqlite\UpdateQuery;
use PHPUnit\Framework\TestCase;

class SqliteQueryFactoryTest extends TestCase
{
    private SqliteQueryFactory $factory;

    public function setUp(): void
    {
        $this->factory = new SqliteQueryFactory();
    }

    public function testInsertQueryInstance(): void
    {
        $insertQuery = $this->factory->insert('users', ['user' => 'james']);
        $this->assertInstanceOf(InsertQuery::class, $insertQuery);
    }

    public function testUpdateQueryInstance(): void
    {
        $selectQuery = $this->factory->update('users', ['foo' => 'bar']);
        $this->assertInstanceOf(UpdateQuery::class, $selectQuery);
    }
}
