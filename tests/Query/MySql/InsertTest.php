<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\MySql;

use Latitude\QueryBuilder\TestCase;

class InsertTest extends TestCase
{
    use MySqlEngineSetup;

    public function testCalcFoundRows(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->ignore(true);

        $this->assertSql('INSERT IGNORE INTO `users` (`username`) VALUES (?)', $insert);
        $this->assertParams(['james'], $insert);
    }

    public function testIgnore(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->ignore();

        $this->assertSql('INSERT IGNORE INTO `users` (`username`) VALUES (?)', $insert);
        $this->assertParams(['james'], $insert);
    }

    public function testIgnoreOverwritesDuplicateKeyUpdate(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->onDuplicateKeyUpdate(
                [
                    'username' => 'rick'
                ]
            )
            ->ignore();

        $this->assertSql('INSERT IGNORE INTO `users` (`username`) VALUES (?)', $insert);
        $this->assertParams(['james'], $insert);
    }

    public function testOnDuplicateKeyUpdate(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->onDuplicateKeyUpdate(
                [
                    'username' => 'rick'
                ]
            );

        $this->assertSql('INSERT INTO `users` (`username`) VALUES (?) ON DUPLICATE KEY UPDATE `username` = ?', $insert);
        $this->assertParams(['james', 'rick'], $insert);
    }
}
