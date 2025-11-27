<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Postgres;

use Latitude\QueryBuilder\TestCase;

class InsertTest extends TestCase
{
    use PostgresEngineSetup;

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

    public function testInsertIgnore(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->ignoreOnConstraint([
                'email'
            ]);

        $this->assertSql('INSERT INTO "users" ("username") VALUES (?) ON CONFLICT ("email") DO NOTHING', $insert);
        $this->assertParams(['james'], $insert);
    }

    public function testUpsert(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->updateOnConstraint(
                'users_uniq',
                [
                    'username' => 'rick'
                ]
            );

        $this->assertSql('INSERT INTO "users" ("username") VALUES (?) ON CONFLICT "users_uniq" DO UPDATE "username" = ?', $insert);
        $this->assertParams(['james', 'rick'], $insert);
    }
}
