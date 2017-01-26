<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\MySQL;

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

    public function testEscape()
    {
        $this->assertSame('`id`', $this->identifier->escape('id'));
    }
}
