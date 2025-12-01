<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Sqlite;

use Latitude\QueryBuilder\TestCase;

class InsertTest extends TestCase
{
    use SqliteEngineSetup;

    public function testReturning(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->returning('id');

        $this->assertSql('INSERT INTO "users" ("username") VALUES (?) RETURNING "id"', $insert);
        $this->assertParams(['james'], $insert);
    }

    public function testOnConflictDoNothing(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->ignoreOnConstraint(['email']);

        $this->assertSql('INSERT INTO "users" ("username") VALUES (?) ON CONFLICT ("email") DO NOTHING', $insert);
        $this->assertParams(['james'], $insert);
    }

    public function testOnConflictDoUpdate(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->updateOnConstraint(
                ['username'],
                [
                    'username' => 'rick'
                ]
            );

        $this->assertSql('INSERT INTO "users" ("username") VALUES (?) ON CONFLICT ("username") DO UPDATE "username" = ?', $insert);
        $this->assertParams(['james', 'rick'], $insert);
    }
}
