<?php

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\identify;

class MySqlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->engine = new MySqlEngine();
    }

    public function testIdentifier(): void
    {
        $field = identify('id');

        $this->assertSql('`id`', $field);
    }
}
