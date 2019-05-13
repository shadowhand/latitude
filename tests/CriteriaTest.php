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

        $this->assertSql('employees.role IN (?, ?)', $criteria);
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

        $this->assertSql('not (id IN (?, ?))', $criteria);
        $this->assertParams([1, 2], $criteria);
    }

    public function testNullComparision()
    {
        $criteria = $this->factory->criteria('users.active = null');

        $this->assertSql('users.active IS NULL', $criteria);

        $criteria = $this->factory->criteria('users.active != null');

        $this->assertSql('users.active IS NOT NULL', $criteria);
    }

    public function testMethodNotSupported()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('id.foo()');

        $this->factory->criteria('id.foo() = 1');
    }

    public function testGrouping()
    {
        $criteria = $this->factory->criteria('(users.id = 25 OR users.role = "admin") AND users.active = true');
        $this->assertSql('(users.id = ? OR users.role = ?) AND users.active = true', $criteria);
        $this->assertParams([25, 'admin'], $criteria);

        $criteria = $this->factory->criteria('users.active = true AND users.id = 33 OR users.role = "operator"');
        $this->assertSql('(users.active = true AND users.id = ? OR users.role = ?)', $criteria);
        $this->assertParams([33, 'operator'], $criteria);

        $criteria = $this->factory->criteria('users.active = true AND (users.id = 68 OR users.role = "admin")');
        $this->assertSql('users.active = true AND (users.id = ? OR users.role = ?)', $criteria);
        $this->assertParams([68, 'admin'], $criteria);

        $criteria = $this->factory->criteria(
            'users.active = true AND (users.id = 69 OR users.role = "admin" OR users.name = "John")'
        );
        $this->assertSql('users.active = true AND (users.id = ? OR (users.role = ? OR users.name = ?))', $criteria);
        $this->assertParams([69, 'admin', 'John'], $criteria);

        $criteria = $this->factory->criteria(
            'users.active = true AND (users.id = 34 OR users.role = "admin" AND users.name = "Sam")'
        );
        $this->assertSql('users.active = true AND (users.id = ? OR users.role = ? AND users.name = ?)', $criteria);
        $this->assertParams([34, 'admin', 'Sam'], $criteria);

        $criteria = $this->factory->criteria(
            'users.active = true AND (users.id = 71 OR users.role = "admin" ' .
            'AND (users.name = "Adam" OR users.name = "Natalie"))'
        );
        $this->assertSql(
            'users.active = true AND (users.id = ? OR users.role = ? AND (users.name = ? OR users.name = ?))',
            $criteria
        );
        $this->assertParams([71, 'admin', 'Adam', 'Natalie'], $criteria);
    }
}
