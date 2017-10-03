<?php

namespace Latitude\QueryBuilder\MySQL;

use Latitude\QueryBuilder\Conditions;
use Latitude\QueryBuilder\Expression;
use PHPUnit\Framework\TestCase;
class DeleteQueryTest extends TestCase
{
    public function testWithoutOrderByAndLimit()
    {
        $table = 'users';
        $delete = DeleteQuery::make($table)->where(Conditions::make('last_login IS NULL'));
        $this->assertSame('DELETE FROM users WHERE last_login IS NULL', $delete->sql());
        $this->assertSame([], $delete->params());
    }
    public function testOrderBy()
    {
        $table = 'users';
        $delete = DeleteQuery::make($table)->where(Conditions::make('last_login IS NULL'))->orderBy(['username', 'DESC'], ['id']);
        $this->assertSame('DELETE FROM users WHERE last_login IS NULL ORDER BY username DESC, id', $delete->sql());
        $this->assertSame([], $delete->params());
    }
    public function testOrderByWithExpression()
    {
        $table = 'users';
        $delete = DeleteQuery::make($table)->where(Conditions::make('last_login IS NULL'))->orderBy([Expression::make('LOWER(u.period)'), 'desc']);
        $this->assertSame('DELETE FROM users WHERE last_login IS NULL ORDER BY LOWER(u.period) DESC', $delete->sql());
        $this->assertSame([], $delete->params());
    }
    public function testLimit()
    {
        $table = 'users';
        $map = ['username' => 'mr-smith'];
        $delete = DeleteQuery::make($table)->where(Conditions::make('last_login IS NULL'))->limit(50);
        $this->assertSame('DELETE FROM users WHERE last_login IS NULL LIMIT 50', $delete->sql());
        $this->assertSame([], $delete->params());
    }
}