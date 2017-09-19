<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Latitude\QueryBuilder\Expression as e;
use Latitude\QueryBuilder\Conditions as c;
use PHPUnit\Framework\TestCase;

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

    public function testSelectDistinct()
    {
        $select = SelectQuery::make()
            ->distinct(true)
            ->from('users');

        $this->assertSame(
            'SELECT DISTINCT * FROM users',
            $select->sql()
        );

        $this->assertSame([], $select->params());
    }

    public function testSelectDistinctFalse()
    {
        $select = SelectQuery::make()
            ->distinct(false)
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

    public function testParams()
    {
        $select = SelectQuery::make(...[
                'e.id',
                'e.first_name',
                'e.last_name',
                e::make('COUNT(%s) AS %s', 's.id', 'shift_count'),
            ])
            ->from('employees e')
            ->where(c::make('e.id > ?', 3))
            ->having(c::make('shift_count > ?', 4))
            ->join('shifts s', c::make('s.type = ?', 1)->orWith('s.day = ?', 2));

        $this->assertSame([1, 2, 3, 4], $select->params());
    }

    /**
     * @dataProvider dataJoin
     */
    public function testJoin(string $method, string $type)
    {
        $select = SelectQuery::make()
            ->from('users')
            ->$method('roles', c::make('users.role_id = roles.id'))
            ->$method('devices', c::make('users.devices_id = devices.id'))
            ;

        $this->assertSame(
            "SELECT * FROM users $type roles ON users.role_id = roles.id $type devices ON users.devices_id = devices.id",
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
        $select = SelectQuery::make(e::make('COUNT(*) AS %s', 'total'))
            ->from('users')
            ->groupBy('role_id')
            ->having(c::make('total > ?', 5));

        $this->assertSame(
            'SELECT COUNT(*) AS total FROM users GROUP BY role_id HAVING total > ?',
            $select->sql()
        );

        $this->assertSame([5], $select->params());
    }

    public function testGroupByWithExpression()
    {
        $select = SelectQuery::make(
                e::make('COUNT(*) AS %s', 'total'),
                e::make('DATE(created_at) AS %s', 'date')
            )
            ->from('users')
            ->groupBy(e::make('DATE(created_at)'))
            ;

        $this->assertSame(
            'SELECT COUNT(*) AS total, DATE(created_at) AS date FROM users GROUP BY DATE(created_at)',
            $select->sql()
        );
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

    public function testOrderByWithExpression()
    {
        $select = SelectQuery::make()
            ->from('users u')
            ->orderBy([e::make('LOWER(u.period)'), 'desc']);

        $this->assertSame(
            'SELECT * FROM users AS u ORDER BY LOWER(u.period) DESC',
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

    public function testLimitReset()
    {
        $select = SelectQuery::make()
            ->from('users')
            ->limit(50);

        $select->limit(null);

        $this->assertSame(
            'SELECT * FROM users',
            $select->sql()
        );
    }

    public function testOffsetReset()
    {
        $select = SelectQuery::make()
            ->from('users')
            ->offset(100)
            ->limit(50);

        $select->offset(null);

        $this->assertSame(
            'SELECT * FROM users LIMIT 50',
            $select->sql()
        );
    }

    public function testSubselect()
    {
        $user_ids_from_orders = SelectQuery::make('user_id')
            ->from('orders')
            ->where(c::make('placed_at BETWEEN ? AND ?', '2017-01-01', '2017-12-31'));

        $select = SelectQuery::make()
            ->from('users')
            ->where(
                c::make('id IN (?)', $user_ids_from_orders)
                ->with('deleted_at IS NULL')
                ->with('created_at BETWEEN ? AND ?', '2016-12-15', '2016-12-25')
            );

        $expected = 'SELECT * FROM users WHERE id IN (' .
            'SELECT user_id FROM orders WHERE placed_at BETWEEN ? AND ?' .
            ') AND deleted_at IS NULL AND created_at BETWEEN ? AND ?';

        $this->assertSame($expected, $select->sql());

        $this->assertSame(
            [
                '2017-01-01',
                '2017-12-31',
                '2016-12-15',
                '2016-12-25',
            ],
            $select->params()
        );
    }
}
