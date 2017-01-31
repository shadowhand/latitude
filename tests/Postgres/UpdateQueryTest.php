<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Postgres;

use Latitude\QueryBuilder\Conditions;
use PHPUnit_Framework_TestCase as TestCase;

class UpdateQueryTest extends TestCase
{
    public function testUpdate()
    {
        $table = 'users';
        $map = [
            'username' => 'mr-smith',
        ];

        $update = UpdateQuery::make($table, $map)
            ->where(
                Conditions::make('username = ?', 'jsmith')
            )
            ->returning(['updated_at']);

        $this->assertSame(
            'UPDATE users SET username = ? WHERE username = ? RETURNING updated_at',
            $update->sql()
        );

        $this->assertSame(
            ['mr-smith', 'jsmith'],
            $update->params()
        );
    }

    public function testUpdateWithoutReturning()
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
}
