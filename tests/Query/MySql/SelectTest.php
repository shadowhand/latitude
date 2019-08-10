<?php

namespace Latitude\QueryBuilder\Query\MySql;

use Latitude\QueryBuilder\TestCase;

class SelectTest extends TestCase
{
    use MySqlEngineSetup;

    public function testCalcFoundRows(): void
    {
        $select = $this->factory
            ->select()
            ->calcFoundRows(true)
            ->from('users')
            ->limit(10);

        $this->assertSql('SELECT SQL_CALC_FOUND_ROWS * FROM `users` LIMIT 10', $select);
        $this->assertParams([], $select);
    }
}
