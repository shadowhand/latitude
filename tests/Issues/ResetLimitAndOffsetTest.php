<?php

namespace Latitude\QueryBuilder\Issues;

use Latitude\QueryBuilder\TestCase;

/**
 * @link https://github.com/shadowhand/latitude/issues/66
 */
class ResetLimitAndOffsetTest extends TestCase
{
    public function testResetLimit(): void
    {
        $query = $this->factory->select()->from('users')->limit(5)->limit(null);

        $this->assertSql('SELECT * FROM users', $query);
    }

    public function testResetOffset(): void
    {
        $query = $this->factory->select()->from('users')->offset(5)->offset(null);

        $this->assertSql('SELECT * FROM users', $query);
    }
}
