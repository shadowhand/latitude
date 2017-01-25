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
    /**
     * @dataProvider dataBooleanAndNull
     */
    public function testInsertBooleanAndNull($value, string $expect)
    {
        $table = 'users';
        $map = [
            'username' => 'jsmith',
            'is_vip' => $value,
        ];

        $insert = InsertQuery::make($table, $map);

        $this->assertContains("VALUES (?, $expect)", $insert->sql());
        $this->assertCount(1, $insert->params());
    }

    public function dataBooleanAndNull()
    {
        return [
            // value, expected sql fragment
            'null value' => [null, 'NULL'],
            'true value' => [true, 'TRUE'],
            'false value' => [false, 'FALSE'],
        ];
    }
}
