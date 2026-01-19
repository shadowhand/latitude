<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Firebird;

use Latitude\QueryBuilder\TestCase;

class InsertTest extends TestCase
{
    use FirebirdEngineSetup;

    public function testReturning(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'jay',
            ])
            ->returning('id');

        $this->assertSql('INSERT INTO "users" ("username") VALUES (?) RETURNING "id"', $insert);
        $this->assertParams(['jay'], $insert);
    }
}
