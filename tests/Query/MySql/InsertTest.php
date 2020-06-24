<?php

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
}
