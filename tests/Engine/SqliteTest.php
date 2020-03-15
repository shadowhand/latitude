<?php

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\field;
use function Latitude\QueryBuilder\identify;

class SqliteTest extends TestCase
{
    protected function setUp(): void
    {
        $this->engine = new SqliteEngine();
    }

    public function testIdentifier()
    {
        $field = identify('id');

        $this->assertSql('id', $field);
    }

    public function testBooleanParameterValue()
    {
        $criteria = field('active')->eq(true);
        $sql = $criteria->sql($this->engine);
        $params = $criteria->params($this->engine);
        $this->assertEquals('active = 1', $sql);
        $this->assertEquals([], $params);

        $criteria = field('active')->eq(false);
        $sql = $criteria->sql($this->engine);
        $params = $criteria->params($this->engine);
        $this->assertEquals('active = 0', $sql);
        $this->assertEquals([], $params);

        $criteria = field('active')->eq(null);
        $sql = $criteria->sql($this->engine);
        $params = $criteria->params($this->engine);
        $this->assertEquals('active = NULL', $sql);
        $this->assertEquals([], $params);

        $criteria = field('active')->eq('yes');
        $sql = $criteria->sql($this->engine);
        $params = $criteria->params($this->engine);
        $this->assertEquals('active = ?', $sql);
        $this->assertEquals(['yes'], $params);
    }
}
