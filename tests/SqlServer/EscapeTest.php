<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\SqlServer;

use PHPUnit_Framework_TestCase as TestCase;

class EscapeTest extends TestCase
{
    public function testLike()
    {
        $input = 'string_not%escaped [range]';
        $expected = 'string\\_not\\%escaped \\[range\\]';

        $this->assertSame($expected, Escape::like($input));
    }
}
