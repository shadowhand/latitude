<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\Engine\CommonEngine;
use Latitude\QueryBuilder\Engine\MySqlEngine;
use Latitude\QueryBuilder\Engine\SqlServerEngine;
use Latitude\QueryBuilder\TestCase;

class QueryWithNamedParamsTest extends TestCase
{
    public function testCommonEscape(): void
    {
        $commonEngine = new CommonEngine();

        $query = $commonEngine->makeInsert()
            ->into('users')
            ->columns('firstname?', 'lastname', 'email')
            ->values('rick', 'anderson', 'rick.anderson@mail.com')
            ->compile()
            ->toQueryWithNamedParams();

        $this->assertEquals('INSERT INTO "users" ("firstname?", "lastname", "email") VALUES (:param_1, :param_2, :param_3)', $query->sql());
        $this->assertSame(
            [
                ':param_1' => 'rick',
                ':param_2' => 'anderson',
                ':param_3' => 'rick.anderson@mail.com'
            ],
            $query->params()
        );
    }

    public function testBackTickEscape(): void
    {
        $backTicksEngine = new MySqlEngine();

        $query = $backTicksEngine->makeInsert()
            ->into('users')
            ->columns('firstname?', 'lastname', 'email')
            ->values('rick', 'anderson', 'rick.anderson@mail.com')
            ->compile()
            ->toQueryWithNamedParams('{p%d}');

        $this->assertEquals('INSERT INTO `users` (`firstname?`, `lastname`, `email`) VALUES ({p1}, {p2}, {p3})', $query->sql());
        $this->assertSame(
            [
                '{p1}' => 'rick',
                '{p2}' => 'anderson',
                '{p3}' => 'rick.anderson@mail.com'
            ],
            $query->params()
        );
    }

    public function testBracketsEscape(): void
    {
        $bracketsEngine = new SqlServerEngine();

        $query = $bracketsEngine->makeInsert()
            ->into('users')
            ->columns('firstname?', 'lastname', 'email')
            ->values('rick', 'anderson', 'rick.anderson@mail.com')
            ->compile()
            ->toQueryWithNamedParams(':{%d}');

        $this->assertEquals('INSERT INTO [users] ([firstname?], [lastname], [email]) VALUES (:{1}, :{2}, :{3})', $query->sql());
        $this->assertSame(
            [
                ':{1}' => 'rick',
                ':{2}' => 'anderson',
                ':{3}' => 'rick.anderson@mail.com'
            ],
            $query->params()
        );
    }
}
