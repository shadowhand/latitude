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

    public function testInsertExpression()
    {
        $table = 'users';
        $map = [
            'username' => 'jdoe',
            'created_at' => Expression::make('NOW()'),
        ];

        $insert = InsertQuery::make($table, $map);

        $this->assertSame(
            'INSERT INTO users (username, created_at) VALUES (?, NOW())',
            $insert->sql()
        );
        $this->assertSame(['jdoe'], $insert->params());
    }

    /**
     * Test for issue #6
     */
    public function testMultipleCompile()
    {
        $table = 'users';
        $map = [
            'username' => 'jdoe',
            'is_employee' => false,
            'is_manager' => true,
            'created_at' => Expression::make('NOW()'),
            'updated_at' => null,
        ];

        $insert = InsertQuery::make($table, $map);

        $sql = $insert->sql();
        $params = $insert->params();

        $this->assertContains('(?, FALSE, TRUE, NOW(), NULL)', $sql);
        $this->assertSame(['jdoe'], $params);

        // Compile again, verifying the same output
        $this->assertSame($sql, $insert->sql());
        $this->assertSame($params, $insert->params());
    }
}
