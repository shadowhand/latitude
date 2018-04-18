<?php

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\identify;

class MySqlTest extends TestCase
{
    public function setUp()
    {
        $this->engine = new MySqlEngine();
    }

    public function testIdentifier()
    {
        $field = identify('id');

        $this->assertSql('`id`', $field);
    }
}
