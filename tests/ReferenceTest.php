<?php

namespace Latitude\QueryBuilder;

use PHPUnit\Framework\TestCase;
class ReferenceTest extends TestCase
{
    public function testStatement()
    {
        $query = SelectQuery::make()->from('users');
        $this->assertSame($query, reference($query));
    }
    public function testReference()
    {
        $ref = reference('users');
        $this->assertInstanceOf(Reference::class, $ref);
        $this->assertSame('users', $ref->sql());
        $this->assertSame([], $ref->params());
    }
    public function testAlias()
    {
        $ref = reference('users u');
        $this->assertInstanceOf(Alias::class, $ref);
        $this->assertSame('users AS u', $ref->sql());
        $this->assertSame([], $ref->params());
        $this->assertSame(reference('users u')->sql(), reference('users as u')->sql(), 'using the "as" keyword is not required');
    }
}