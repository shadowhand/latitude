<?php

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\alias;
use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\field;
use function Latitude\QueryBuilder\func;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\on;

class SelectTest extends TestCase
{
    public function testSelect(): void
    {
        $select = $this->factory
            ->select()
            ->from('users');

        $this->assertSql('SELECT * FROM users', $select);
        $this->assertParams([], $select);
    }

    public function testDistinct(): void
    {
        $select = $this->factory
            ->select()
            ->distinct();

        $this->assertSql('SELECT DISTINCT *', $select);
        $this->assertParams([], $select);
    }

    public function testColumns(): void
    {
        $select = $this->factory
            ->select('id', 'username')
            ->from('users');

        $this->assertSql('SELECT id, username FROM users', $select);
        $this->assertParams([], $select);
    }

    public function testJoin(): void
    {
        $select = $this->factory
            ->select('u.username', 'r.role', 'c.country')
            ->from(alias('users', 'u'))
            ->join(alias('roles', 'r'), on('u.role_id', 'r.id'))
            ->join(alias('countries', 'c'), on('u.country_id', 'c.id'));

        $expected = implode(' ', [
            'SELECT u.username, r.role, c.country',
            'FROM users AS u',
            'JOIN roles AS r ON u.role_id = r.id',
            'JOIN countries AS c ON u.country_id = c.id',
        ]);

        $this->assertSql($expected, $select);
        $this->assertParams([], $select);
    }

    public function testJoinInner(): void
    {
        $select = $this->factory
            ->select('u.username', 'c.country')
            ->from(alias('users', 'u'))
            ->innerJoin(alias('countries', 'c'), on('u.country_id', 'c.id'));

        $expected = implode(' ', [
            'SELECT u.username, c.country',
            'FROM users AS u',
            'INNER JOIN countries AS c ON u.country_id = c.id',
        ]);

        $this->assertSql($expected, $select);
        $this->assertParams([], $select);
    }

    public function testJoinLeft(): void
    {
        $select = $this->factory
            ->select('u.username', 'c.country')
            ->from(alias('users', 'u'))
            ->leftJoin(alias('countries', 'c'), on('u.country_id', 'c.id'));

        $expected = implode(' ', [
            'SELECT u.username, c.country',
            'FROM users AS u',
            'LEFT JOIN countries AS c ON u.country_id = c.id',
        ]);

        $this->assertSql($expected, $select);
        $this->assertParams([], $select);
    }

    public function testJoinRight(): void
    {
        $select = $this->factory
            ->select('u.username', 'c.country')
            ->from(alias('users', 'u'))
            ->rightJoin(alias('countries', 'c'), on('u.country_id', 'c.id'));

        $expected = implode(' ', [
            'SELECT u.username, c.country',
            'FROM users AS u',
            'RIGHT JOIN countries AS c ON u.country_id = c.id',
        ]);

        $this->assertSql($expected, $select);
        $this->assertParams([], $select);
    }

    public function testJoinFull(): void
    {
        $select = $this->factory
            ->select('u.username', 'c.country')
            ->from(alias('users', 'u'))
            ->fullJoin(alias('countries', 'c'), on('u.country_id', 'c.id'));

        $expected = implode(' ', [
            'SELECT u.username, c.country',
            'FROM users AS u',
            'FULL JOIN countries AS c ON u.country_id = c.id',
        ]);

        $this->assertSql($expected, $select);
        $this->assertParams([], $select);
    }

    public function testWhere(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->where(field('id')->eq(1));

        $this->assertSql('SELECT * FROM users WHERE id = ?', $select);
        $this->assertParams([1], $select);
    }

    public function testWhereAnd(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->andWhere(field('id')->eq(1))
            ->andWhere(field('username')->eq('admin'));

        $this->assertSql('SELECT * FROM users WHERE id = ? AND username = ?', $select);
        $this->assertParams([1, 'admin'], $select);
    }

    public function testWhereOr(): void
    {
        $select = $this->factory
            ->select()
            ->from('countries')
            ->orWhere(field('country')->eq('JP'))
            ->orWhere(field('country')->eq('CN'));

        $this->assertSql('SELECT * FROM countries WHERE country = ? OR country = ?', $select);
        $this->assertParams(['JP', 'CN'], $select);
    }

    public function testGroupBy(): void
    {
        $select = $this->factory
            ->select(
                alias(func('COUNT', 'id'), 'total')
            )
            ->from('employees')
            ->groupBy('department');

        $expected = implode(' ', [
            'SELECT COUNT(id) AS total',
            'FROM employees',
            'GROUP BY department',
        ]);

        $this->assertSql($expected, $select);
        $this->assertParams([], $select);
    }

    public function testHaving(): void
    {
        $select = $this->factory
            ->select(
                'department',
                alias($sum = func('SUM', 'salary'), 'total')
            )
            ->from('employees')
            ->groupBy('department')
            ->having(field($sum)->gt(5000));

        $expected = implode(' ', [
            'SELECT department, SUM(salary) AS total',
            'FROM employees',
            'GROUP BY department',
            'HAVING SUM(salary) > ?',
        ]);

        $this->assertSql($expected, $select);
        $this->assertParams([5000], $select);
    }

    public function testOrderBy(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->orderBy('birthday');

        $this->assertSql('SELECT * FROM users ORDER BY birthday', $select);
        $this->assertParams([], $select);
    }

    public function testOrderByDirection(): void
    {
        $select = $this->factory
            ->select(
                'u.id',
                'u.username',
                alias(func('COUNT', 'l.id'), 'total')
            )
            ->from(alias('users', 'u'))
            ->join(alias('logins', 'l'), on('u.id', 'l.user_id'))
            ->groupBy('l.user_id')
            ->orderBy('u.username')
            ->orderBy('total', 'desc');

        $expected = implode(' ', [
            'SELECT u.id, u.username, COUNT(l.id) AS total',
            'FROM users AS u',
            'JOIN logins AS l ON u.id = l.user_id',
            'GROUP BY l.user_id',
            'ORDER BY u.username, total DESC',
        ]);

        $this->assertSql($expected, $select);
        $this->assertParams([], $select);
    }

    public function testOrderByReset(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->orderBy('birthday');

        $select->orderBy(null);

        $this->assertSql('SELECT * FROM users', $select);
        $this->assertParams([], $select);
    }

    public function testOrderByExpression(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->orderBy(express("FIELD(%s, 'off')", identify('status')), 'DESC');

        $this->assertSql("SELECT * FROM users ORDER BY FIELD(status, 'off') DESC", $select);
        $this->assertParams([], $select);
    }

    public function testOffsetLimit(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->limit(10)
            ->offset(100);

        $this->assertSql('SELECT * FROM users LIMIT 10 OFFSET 100', $select);
        $this->assertParams([], $select);
    }

    public function testUnion(): void
    {
        $a = $this->factory->select('supplier_id')->from('suppliers');
        $b = $this->factory->select('supplier_id')->from('orders');

        $union = $a->union($b)->orderBy('supplier_id', 'desc');

        $expected = implode(' ', [
            'SELECT supplier_id FROM suppliers',
            'UNION',
            'SELECT supplier_id FROM orders',
            'ORDER BY supplier_id DESC'
        ]);

        $this->assertSql($expected, $union);
        $this->assertParams([], $union);
    }

    public function testUnionAll(): void
    {
        $a = $this->factory->select('first_name', 'last_name')->from('employees');
        $b = $this->factory->select('first_name', 'last_name')->from('customers');
        $c = $this->factory->select('first_name', 'last_name')->from('partners');

        $union = $a->unionAll($b)->unionAll($c);

        $expected = implode(' ', [
            'SELECT first_name, last_name FROM employees',
            'UNION ALL',
            'SELECT first_name, last_name FROM customers',
            'UNION ALL',
            'SELECT first_name, last_name FROM partners',
        ]);

        $this->assertSql($expected, $union);
        $this->assertParams([], $union);
    }
}
