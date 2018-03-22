<?php

namespace Latitude\QueryBuilder\Query\Postgres;

use Latitude\QueryBuilder\TestCase;

class InsertTest extends TestCase
{
    use PostgresEngineSetup;

    public function testReturning()
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->returning('id');

        $this->assertSql('INSERT INTO "users" ("username") VALUES (?) RETURNING "id"', $insert);
        $this->assertParams(['james'], $insert);
    }
}
