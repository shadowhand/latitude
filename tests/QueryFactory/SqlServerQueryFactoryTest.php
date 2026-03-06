<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Query\SqlServer\DeleteQuery;
use Latitude\QueryBuilder\Query\SqlServer\SelectQuery;
use PHPUnit\Framework\TestCase;

class SqlServerQueryFactoryTest extends TestCase
{
    private SqlServerQueryFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new SqlServerQueryFactory();
    }

    public function testDeleteQueryInstance(): void
    {
        $deleteQuery = $this->factory->delete('users');
        $this->assertInstanceOf(DeleteQuery::class, $deleteQuery);
    }

    public function testSelectQueryInstance(): void
    {
        $selectQuery = $this->factory->select('*');
        $this->assertInstanceOf(SelectQuery::class, $selectQuery);
    }
}
