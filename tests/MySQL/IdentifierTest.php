<?php

namespace Latitude\QueryBuilder\MySQL;

use PHPUnit\Framework\TestCase;
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
    public function testEscape()
    {
        $this->assertSame('`id`', $this->identifier->escape('id'));
    }
}