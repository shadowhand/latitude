<?php

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\alias;
use function Latitude\QueryBuilder\identify;

class IdentifierTest extends TestCase
{
    public function testIdentity()
    {
        $field = identify('id');

        $this->assertSql('id', $field);
        $this->assertParams([], $field);
    }

    public function testAlias()
    {
        $alias = alias('users', 'u');

        $this->assertSql('users AS u', $alias);
        $this->assertParams([], $alias);
    }

    public function testQualified()
    {
        $field = identify('public.users.username');

        $this->assertSql('public.users.username', $field);
        $this->assertParams([], $field);
    }

    public function testQualifiedAlias()
    {
        $alias = alias('u.id', 'user_id');

        $this->assertSql('u.id AS user_id', $alias);
        $this->assertParams([], $alias);
    }
}
