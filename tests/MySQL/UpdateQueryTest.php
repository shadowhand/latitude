<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\MySQL;

use Latitude\QueryBuilder\Conditions;
use Latitude\QueryBuilder\Expression;
use PHPUnit\Framework\TestCase;

class UpdateQueryTest extends TestCase
{
    public function testWithoutOrderByAndLimit()
    {
        $table = 'users';
        $map = [
            'username' => 'mr-smith',
        ];

        $update = UpdateQuery::make($table, $map)
            ->where(
                Conditions::make('username = ?', 'jsmith')
            );

        $this->assertSame(
            'UPDATE users SET username = ? WHERE username = ?',
            $update->sql()
        );

        $this->assertSame(
            ['mr-smith', 'jsmith'],
            $update->params()
        );
    }

    public function testOrderBy()
    {
        $table = 'users';
        $map = [
            'username' => 'mr-smith',
        ];

        $update = UpdateQuery::make($table, $map)
            ->where(
                Conditions::make('username = ?', 'jsmith')
            )->orderBy(['username', 'DESC'], ['id']);

        $this->assertSame(
            'UPDATE users SET username = ? WHERE username = ? ORDER BY username DESC, id',
            $update->sql()
        );

        $this->assertSame(
            ['mr-smith', 'jsmith'],
            $update->params()
        );
    }

    public function testOrderByWithExpression()
    {
        $table = 'users';
        $map = [
            'username' => 'mr-smith',
        ];

        $update = UpdateQuery::make($table, $map)
            ->where(
                Conditions::make('username = ?', 'jsmith')
            )->orderBy([Expression::make('LOWER(u.period)'), 'desc']);

        $this->assertSame(
            'UPDATE users SET username = ? WHERE username = ? ORDER BY LOWER(u.period) DESC',
            $update->sql()
        );

        $this->assertSame(
            ['mr-smith', 'jsmith'],
            $update->params()
        );
    }

    public function testLimit()
    {
        $table = 'users';
        $map = [
            'username' => 'mr-smith',
        ];

        $update = UpdateQuery::make($table, $map)
            ->where(
                Conditions::make('username = ?', 'jsmith')
            )->limit(50);

        $this->assertSame(
            'UPDATE users SET username = ? WHERE username = ? LIMIT 50',
            $update->sql()
        );

        $this->assertSame(
            ['mr-smith', 'jsmith'],
            $update->params()
        );
    }
}