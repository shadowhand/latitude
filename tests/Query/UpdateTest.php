<?php

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\field;

class UpdateTest extends TestCase
{
    public function testUpdate()
    {
        $update = $this->engine
            ->update('users');

        $this->assertSql('UPDATE users', $update);
        $this->assertParams([], $update);
    }

    public function testSet()
    {
        $update = $this->engine
            ->update('users', [
                'last_login' => null,
            ]);

        $this->assertSql('UPDATE users SET last_login = ?', $update);
        $this->assertParams([null], $update);
    }

    public function testWhere()
    {
        $update = $this->engine
            ->update('users', [
                'username' => 'wonder_woman',
            ])
            ->where(field('id')->eq(50));

        $this->assertSql('UPDATE users SET username = ? WHERE id = ?', $update);
        $this->assertParams(['wonder_woman', 50], $update);
    }
}
