<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Firebird;

use Latitude\QueryBuilder\TestCase;

class DeleteTest extends TestCase
{
    use FirebirdEngineSetup;

    public function testLimit(): void
    {
        $delete = $this->factory
            ->delete('users')
            ->limit(10);

        $this->assertSql('DELETE FROM "users" ROWS 10', $delete);
        $this->assertParams([], $delete);
    }

    public function testLimitWithReturning(): void
    {
        $delete = $this->factory
            ->delete('users')
            ->limit(10)
            ->returning('id');

        $this->assertSql('DELETE FROM "users" ROWS 10 RETURNING "id"', $delete);
        $this->assertParams([], $delete);
    }
}
