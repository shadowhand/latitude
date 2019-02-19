<?php

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\identify;

class PostgresTest extends TestCase
{
    protected function setUp()
    {
        $this->engine = new PostgresEngine();
    }

    public function testIdentifier()
    {
        $field = identify('id');

        $this->assertSql('"id"', $field);
    }
}
