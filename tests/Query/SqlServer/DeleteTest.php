<?php

namespace Latitude\QueryBuilder\Query\SqlServer;

use Latitude\QueryBuilder\TestCase;

class DeleteTest extends TestCase
{
    use SqlServerEngineSetup;

    public function testLimit(): void
    {
        $delete = $this->factory
            ->delete('users')
            ->limit(10);

        $this->assertSql('DELETE TOP(10) FROM [users]', $delete);
        $this->assertParams([], $delete);
    }
}
