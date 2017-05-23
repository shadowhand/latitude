<?php

namespace Latitude\QueryBuilder;

use PHPUnit\Framework\TestCase;
class InsertMultipleQueryTest extends TestCase
{
    public function testInsert()
    {
        $table = 'tokens';
        $columns = ['token', 'created_at'];
        $now = Expression::make('NOW()');
        $insert = InsertMultipleQuery::make($table, $columns);
        $insert->append(['a', $now]);
        $insert->append(['b', $now]);
        $insert->append(['c', $now]);
        $this->assertSame('INSERT INTO tokens (token, created_at) VALUES (?, NOW()), (?, NOW()), (?, NOW())', $insert->sql());
        $this->assertSame(['a', 'b', 'c'], $insert->params());
    }
}