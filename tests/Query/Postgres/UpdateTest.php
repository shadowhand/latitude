<?php

namespace Latitude\QueryBuilder\Query\Postgres;

use Latitude\QueryBuilder\TestCase;

class UpdateTest extends TestCase
{
    use PostgresEngineSetup;

    public function testReturning(): void
    {
        $update = $this->factory
            ->update('users', [
                'last_login' => null
            ])
            ->returning('id');

        $this->assertSql('UPDATE "users" SET "last_login" = NULL RETURNING "id"', $update);
        $this->assertParams([], $update);
    }
}
