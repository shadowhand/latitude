<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

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

        $insert = InsertQuery::make($table, $map);

        $this->assertSame(
            'INSERT INTO users (username, password) VALUES (?, ?)',
            $insert->sql()
        );

        $this->assertSame(
            \array_values($map),
            $insert->params()
        );
    }
}
