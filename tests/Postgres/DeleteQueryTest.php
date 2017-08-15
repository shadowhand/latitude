<?php

namespace Latitude\QueryBuilder\Postgres;

use Latitude\QueryBuilder\Conditions;
use PHPUnit\Framework\TestCase;
class DeleteQueryTest extends TestCase
{
    public function testDelete()
    {
        $table = 'users';
        $delete = DeleteQuery::make($table)->where(Conditions::make('last_login IS NULL'))->returning(['id']);
        $this->assertSame('DELETE FROM users WHERE last_login IS NULL RETURNING id', $delete->sql());
    }
}