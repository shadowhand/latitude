<?php

namespace Latitude\QueryBuilder\Query\Postgres;

use Latitude\QueryBuilder\TestCase;

class UpdateTest extends TestCase
{
    use PostgresEngineSetup;

    public function testReturning()
    {
        $update = $this->engine
            ->update('users', [
                'last_login' => null
            ])
            ->returning('id');

        $this->assertSql('UPDATE "users" SET "last_login" = ? RETURNING "id"', $update);
        $this->assertParams([null], $update);
    }
}
