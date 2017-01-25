<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use PHPUnit_Framework_TestCase as TestCase;
use Latitude\QueryBuilder\Conditions as c;

class SelectQueryTest extends TestCase
{
    public function testSelect()
    {
        $select = SelectQuery::make()
            ->from('users');

        $this->assertSame(
            'SELECT * FROM users',
            $select->sql()
        );

        $this->assertSame([], $select->params());
    }

    public function testMultipleTables()
    {
        $select = SelectQuery::make()
            ->from('users', 'roles');

        $this->assertSame(
            'SELECT * FROM users, roles',
            $select->sql()
        );
    }

    public function testColumns()
    {
        $select = SelectQuery::make('id', 'username')
            ->from('users');

        $this->assertSame(
            'SELECT id, username FROM users',
            $select->sql()
        );
    }

    /**
     * @dataProvider dataJoin
     */
    public function testJoin(string $method, string $type)
    {
        $select = SelectQuery::make()
            ->from('users')
            ->$method('roles', c::make('users.role_id = roles.id'));

        $this->assertSame(
            "SELECT * FROM users $type roles ON users.role_id = roles.id",
            $select->sql()
        );
    }

    public function dataJoin()
    {
        return [
            // method, type
            ['join', 'JOIN'],
            ['innerJoin', 'INNER JOIN'],
            ['outerJoin', 'OUTER JOIN'],
            ['rightJoin', 'RIGHT JOIN'],
            ['rightOuterJoin', 'RIGHT OUTER JOIN'],
            ['leftJoin', 'LEFT JOIN'],
            ['leftOuterJoin', 'LEFT OUTER JOIN'],
            ['fullJoin', 'FULL JOIN'],
            ['fullOuterJoin', 'FULL OUTER JOIN'],
        ];
    }

    public function testWhere()
    {
        $select = SelectQuery::make()
            ->from('users')
            ->where(c::make('id = ?', 1));

        $this->assertSame(
            'SELECT * FROM users WHERE id = ?',
            $select->sql()
        );

        $this->assertSame([1], $select->params());
    }

    public function testGroupByHaving()
    {
        $select = SelectQuery::make('COUNT(id) AS total')
            ->from('users')
            ->groupBy('role_id')
            ->having(c::make('total > ?', 5));

        $this->assertSame(
            'SELECT COUNT(id) AS total FROM users GROUP BY role_id HAVING total > ?',
            $select->sql()
        );

        $this->assertSame([5], $select->params());
    }

    public function testOrderBy()
    {
        $select = SelectQuery::make()
            ->from('users')
            ->orderBy(['last_login', 'desc'], ['id']);

        $this->assertSame(
            'SELECT * FROM users ORDER BY last_login DESC, id',
            $select->sql()
        );
    }

    public function testLimitOffset()
    {
        $select = SelectQuery::make()
            ->from('users')
            ->limit(50)
            ->offset(0);

        $this->assertSame(
            'SELECT * FROM users LIMIT 50 OFFSET 0',
            $select->sql()
        );
    }
}
