<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Common;

use PHPUnit_Framework_TestCase as TestCase;

class IdentifierTest extends TestCase
{
    /**
     * @var Identifier
     */
    private $identifier;

    public function setUp()
    {
        $this->identifier = Identifier::make();
    }

    public function testStar()
    {
        $this->assertSame('*', $this->identifier->escape('*'));
    }

    public function testEscape()
    {
        $this->assertSame('"id"', $this->identifier->escape('id'));
    }

    public function testQualified()
    {
        $this->assertSame('"users"."id"', $this->identifier->escapeQualified('users.id'));
    }

    public function testAlias()
    {
        $aliases = [
            'id userId',
            'id as userId',
            'id AS userId',
        ];

        foreach ($aliases as $alias) {
            $this->assertSame('"id" AS "userId"', $this->identifier->escapeAlias($alias));
        }

        $this->assertSame('"users"."id" AS "userId"', $this->identifier->escapeAlias('users.id userId'));
    }

    public function testAll()
    {
        $identifiers = [
            'users',
        ];

        $this->assertSame(
            \array_map([$this->identifier, 'escape'], $identifiers),
            $this->identifier->all($identifiers)
        );
    }

    public function testAllQualified()
    {
        $identifiers = [
            'users',
            'users.id',
        ];

        $this->assertSame(
            \array_map([$this->identifier, 'escapeQualified'], $identifiers),
            $this->identifier->allQualified($identifiers)
        );
    }

    public function testExpression()
    {
        $this->assertSame(
            'id = ?',
            $this->identifier->escapeExpression('id = ?')
        );

        $this->assertSame(
            '"u"."id" = ?',
            $this->identifier->escapeExpression('u.id = ?')
        );

        $this->assertSame(
            '"u"."role_id" = "r"."id"',
            $this->identifier->escapeExpression('u.role_id = r.id')
        );

        $this->assertSame(
            '"u"."friends" > COUNT("f"."id")',
            $this->identifier->escapeExpression('u.friends > COUNT(f.id)')
        );
    }
}
