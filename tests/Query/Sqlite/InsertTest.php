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

        if (PHP_VERSION_ID < 80100) {
            $this->assertSql('INSERT INTO "users" ("username") VALUES (?)', $insert);
            $this->assertParams(['james'], $insert);
        } else {
            $this->assertSql('INSERT INTO "users" ("username") VALUES (?) RETURNING "id"', $insert);
            $this->assertParams(['james'], $insert);
        }
    }

    public function testOnConflictDoNothing(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->onConflictDoNothing(['email']);

        if (PHP_VERSION_ID < 80000) {
            $this->assertSql('INSERT INTO "users" ("username") VALUES (?)', $insert);
            $this->assertParams(['james'], $insert);
        } else {
            $this->assertSql('INSERT INTO "users" ("username") VALUES (?) ON CONFLICT ("email") DO NOTHING', $insert);
            $this->assertParams(['james'], $insert);
        }
    }

    public function testOnConflictDoUpdate(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->onConflictDoUpdate(
                ['username'],
                [
                    'username' => 'rick'
                ]
            );

        if (PHP_VERSION_ID < 80000) {
            $this->assertSql('INSERT INTO "users" ("username") VALUES (?)', $insert);
            $this->assertParams(['james'], $insert);
        } else {
            $this->assertSql('INSERT INTO "users" ("username") VALUES (?) ON CONFLICT ("username") DO UPDATE SET "username" = ?', $insert);
            $this->assertParams(['james', 'rick'], $insert);
        }
    }

    public function testOnConflictDoUpdateBulkInsert(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'foo',
                'email' => 'foo@email.com',
            ])
            ->values('bar', 'bar@email.com')
            ->values('baz', 'baz@email.com')
            ->onConflictDoUpdate(
                ['email'],
                [
                    'username',
                    'email' => '<user contact info removed>',
                ]
            );

        $expected = implode(
            '',
            [
                'INSERT INTO "users" ("username", "email") ',
                'VALUES (?, ?), (?, ?), (?, ?) ',
                'ON CONFLICT ("email") DO UPDATE SET "username" = EXCLUDED."username", "email" = ?'
            ]
        );

        if (PHP_VERSION_ID < 80000) {
            $this->assertSql('INSERT INTO "users" ("username", "email") VALUES (?, ?), (?, ?), (?, ?)', $insert);
            $this->assertParams(
                [
                    'foo',
                    'foo@email.com',
                    'bar',
                    'bar@email.com',
                    'baz',
                    'baz@email.com'
                ],
                $insert
            );
        } else {
            $this->assertSql($expected, $insert);
            $this->assertParams(
                [
                    'foo',
                    'foo@email.com',
                    'bar',
                    'bar@email.com',
                    'baz',
                    'baz@email.com',
                    '<user contact info removed>'
                ],
                $insert
            );
        }
    }
}
