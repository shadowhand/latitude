<?php

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\identify;

class PostgresTest extends TestCase
{
    protected function setUp(): void
    {
        $this->engine = new PostgresEngine();
    }

    public function testIdentifier(): void
    {
        $field = identify('id');

        $this->assertSql('"id"', $field);
    }
}
