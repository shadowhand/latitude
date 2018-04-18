<?php

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\field;

class DeleteTest extends TestCase
{
    public function testDelete()
    {
        $insert = $this->factory
            ->delete('users');

        $this->assertSql('DELETE FROM users', $insert);
        $this->assertParams([], $insert);
    }

    public function testWhere()
    {
        $insert = $this->factory
            ->delete('users')
            ->where(field('id')->eq(5));

        $this->assertParams([5], $insert);
    }
}
