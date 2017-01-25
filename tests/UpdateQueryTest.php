<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

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

    /**
     * @dataProvider dataBooleanAndNull
     */
    public function testUpdateBooleanAndNull($value, string $expect)
    {
        $table = 'users';
        $map = [
            'is_vip' => $value
        ];

        $update = UpdateQuery::make($table, $map)
            ->where(
                Conditions::make('username = ?', 'jsmith')
            );

        $this->assertContains($expect, $update->sql());
        $this->assertCount(1, $update->params());
    }

    public function dataBooleanAndNull()
    {
        return [
            // value, expected sql fragment
            'null value' => [null, 'is_vip = NULL'],
            'true value' => [true, 'is_vip = TRUE'],
            'false value' => [false, 'is_vip = FALSE'],
        ];
    }

    public function testUpdateFailsWithoutWhere()
    {
        $table = 'users';
        $map = [
            'password' => 'bobby-tables-strikes-again',
        ];

        $update = UpdateQuery::make($table, $map);

        $this->expectException(QueryBuilderException::class);
        $this->expectExceptionCode(QueryBuilderException::UPDATE_REQUIRES_WHERE);

        $sql = $update->sql();
    }
}
