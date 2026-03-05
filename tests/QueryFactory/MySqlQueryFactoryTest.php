<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Query\MySql\InsertQuery;
use Latitude\QueryBuilder\Query\MySql\SelectQuery;
use PHPUnit\Framework\TestCase;

class MySqlQueryFactoryTest extends TestCase
{
    private MySqlQueryFactory $factory;

    public function setUp(): void
    {
        $this->factory = new MySqlQueryFactory();
    }

    public function testInsertQueryInstance(): void
    {
        $insertQuery = $this->factory->insert('users', ['user' => 'james']);
        $this->assertInstanceOf(InsertQuery::class, $insertQuery);
    }

    public function testSelectQueryInstance(): void
    {
        $selectQuery = $this->factory->select('*');
        $this->assertInstanceOf(SelectQuery::class, $selectQuery);

        $selectDistinctQuery = $this->factory->selectDistinct('*');
        $this->assertInstanceOf(SelectQuery::class, $selectDistinctQuery);
    }
}
