<?php

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\field;

class UpdateTest extends TestCase
{
    public function testUpdate(): void
    {
        $update = $this->factory
            ->update('users');

        $this->assertSql('UPDATE users', $update);
        $this->assertParams([], $update);
    }

    public function testSet(): void
    {
        $update = $this->factory
            ->update('users', [
                'last_login' => null,
            ]);

        $this->assertSql('UPDATE users SET last_login = NULL', $update);
        $this->assertParams([], $update);
    }

    public function testWhere(): void
    {
        $update = $this->factory
            ->update('users', [
                'username' => 'wonder_woman',
            ])
            ->where(field('id')->eq(50));

        $this->assertSql('UPDATE users SET username = ? WHERE id = ?', $update);
        $this->assertParams(['wonder_woman', 50], $update);
    }
}
