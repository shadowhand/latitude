<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use PHPUnit_Framework_TestCase as TestCase;

class DeleteQueryTest extends TestCase
{
    public function testDelete()
    {
        $table = 'users';

        $delete = DeleteQuery::make($table)
            ->where(
                Conditions::make('last_login IS NULL')
            );

        $this->assertSame(
            'DELETE FROM users WHERE last_login IS NULL',
            $delete->sql()
        );

        $this->assertSame([], $delete->params());
    }

    public function testDeleteFailsWithoutWhere()
    {
        $table = 'users';

        $delete = DeleteQuery::make($table);

        $this->expectException(QueryBuilderException::class);
        $this->expectExceptionCode(QueryBuilderException::DELETE_REQUIRES_WHERE);

        $sql = $delete->sql();
    }
}
