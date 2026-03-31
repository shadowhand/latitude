<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Firebird;

use Latitude\QueryBuilder\TestCase;

class SelectTest extends TestCase
{
    use FirebirdEngineSetup;

    public function testLimitWithoutOffset(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->limit(10);

        $this->assertSql('SELECT * FROM "users" ROWS 10', $select);
        $this->assertParams([], $select);
    }

    public function testLimitWithOffset(): void
    {
        $select = $this->factory
            ->select()
            ->from('users')
            ->offset(5)
            ->limit(10);

        $this->assertSql('SELECT * FROM "users" ROWS 6 TO 15', $select);
        $this->assertParams([], $select);
    }
}
