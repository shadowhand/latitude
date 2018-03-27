<?php

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\search;

class SqlServerTest extends TestCase
{
    public function setUp()
    {
        $this->engine = new SqlServerEngine();
    }

    public function testIdentifier()
    {
        $field = identify('id');

        $this->assertSql('[id]', $field);
    }

    public function testLike()
    {
        $expr = search('username')->contains('[a-z]');

        $this->assertParams(['%\\[a-z\\]%'], $expr);
    }
}
