<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\search;

class SqlServerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->engine = new SqlServerEngine();
    }

    public function testIdentifier(): void
    {
        $field = identify('id');

        $this->assertSql('[id]', $field);

        $field = identify('contains[brackets]');

        $this->assertSql('[contains[brackets]]]', $field);
    }

    public function testLike(): void
    {
        $expr = search('username')->contains('[a-z]');

        $this->assertParams(['%\\[a-z\\]%'], $expr);
    }
}
