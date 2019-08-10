<?php

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\TestCase;

class InsertTest extends TestCase
{
    public function testInsert(): void
    {
        $insert = $this->factory
            ->insert('users');

        $this->assertSql('INSERT INTO users', $insert);
        $this->assertParams([], $insert);
    }

    public function testMap(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'id' => 1,
                'username' => 'admin',
            ]);

        $this->assertSql('INSERT INTO users (id, username) VALUES (?, ?)', $insert);
        $this->assertParams([1, 'admin'], $insert);
    }

    public function testMultiple(): void
    {
        $insert = $this->factory
            ->insert('users')
            ->columns('id', 'username')
            ->values(2, 'jenny')
            ->values(3, 'rick');

        $this->assertSql('INSERT INTO users (id, username) VALUES (?, ?), (?, ?)', $insert);
        $this->assertParams([2, 'jenny', 3, 'rick'], $insert);
    }
}
