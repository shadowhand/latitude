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

    public function testInsertIgnore(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->ignoreOnConstraint(null);

        $this->assertSql('INSERT IGNORE INTO `users` (`username`) VALUES (?)', $insert);
        $this->assertParams(['james'], $insert);
    }

    public function testUpsert(): void
    {
        $insert = $this->factory
            ->insert('users', [
                'username' => 'james',
            ])
            ->updateOnConstraint(
                null,
                [
                    'username' => 'rick'
                ]
            );

        $this->assertSql('INSERT INTO `users` (`username`) VALUES (?) ON DUPLICATE KEY UPDATE `username` = ?', $insert);
        $this->assertParams(['james', 'rick'], $insert);
    }
}
