<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Postgres;

use PHPUnit_Framework_TestCase as TestCase;

class InsertQueryTest extends TestCase
{
    public function testInsert()
    {
        $table = 'users';
        $map = [
            'username' => 'jsmith',
            'password' => 'i-should-be-a-hash',
        ];

        $insert = InsertQuery::make($table, $map)
            ->returning(['id']);

        $this->assertSame(
            'INSERT INTO users (username, password) VALUES (?, ?) RETURNING id',
            $insert->sql()
        );

        $this->assertSame(
            \array_values($map),
            $insert->params()
        );
    }
}
