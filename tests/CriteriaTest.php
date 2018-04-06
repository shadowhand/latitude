<?php

namespace Latitude\QueryBuilder;

use Latitude\QueryBuilder\TestCase;

class CriteriaTest extends TestCase
{
    public function testEquality()
    {
        $criteria = $this->factory->criteria('users.id = 100');

        $this->assertSql('users.id = ?', $criteria);
        $this->assertParams([100], $criteria);
    }

    public function testIn()
    {
        $criteria = $this->factory->criteria('employees.role in ["manager", "supervisor"]');

        $this->assertSql('employees.role in (?, ?)', $criteria);
        $this->assertParams(['manager', 'supervisor'], $criteria);
    }

    public function testPlaceholder()
    {
        $criteria = $this->factory->criteria('users.login_at > ?');

        $this->assertSql('users.login_at > ?', $criteria);
        $this->assertParams([], $criteria);
    }

    public function testFunction()
    {
        $criteria = $this->factory->criteria('count(total) > 5.0');

        $this->assertSql('COUNT(total) > ?', $criteria);
        $this->assertParams([5.0], $criteria);
    }

    public function testNegated()
    {
        $criteria = $this->factory->criteria('not (id in [1, 2])');

        $this->assertSql('not (id in (?, ?))', $criteria);
        $this->assertParams([1, 2], $criteria);
    }

    public function testMethodNotSupported()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('id.foo()');

        $this->factory->criteria('id.foo() = 1');
    }
}
