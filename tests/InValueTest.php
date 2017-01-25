<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use PHPUnit_Framework_TestCase as TestCase;

class InValueTest extends TestCase
{
    public function testValue()
    {
        $in = InValue::make($values = [1, 2, 3]);

        $this->assertSame($values, $in->values());

        // Countable
        $this->assertCount(3, $in);
    }
}
