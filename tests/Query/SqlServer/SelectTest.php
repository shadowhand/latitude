<?php

namespace Latitude\QueryBuilder\Query\SqlServer;

use Latitude\QueryBuilder\TestCase;

class SelectTest extends TestCase
{
    use SqlServerEngineSetup;

    public function testLimitWithoutOffset(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->limit(10);

        // SQL Server requires that OFFSET be defined for LIMIT to work
        $this->assertSql('SELECT * FROM [users]', $select);
        $this->assertParams([], $select);
    }

    public function testOffsetLimit(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->orderBy('id')
            ->offset(0)
            ->limit(10);

        $expected = implode(' ', [
            'SELECT *',
            'FROM [users]',
            'ORDER BY [id]',
            'OFFSET 0 ROWS',
            'FETCH NEXT 10 ROWS ONLY',
        ]);

        $this->assertSql($expected, $select);
        $this->assertParams([], $select);
    }
}
