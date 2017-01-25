<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use PHPUnit_Framework_TestCase as TestCase;

class EscapeTest extends TestCase
{
    public function testLike()
    {
        $input = 'string_not%escaped';
        $expected = 'string\\_not\\%escaped';

        $this->assertSame($expected, Escape::like($input));
    }

    public function testLikeAny()
    {
        $input = 'a % string';
        $expected = '%a \\% string%';

        $this->assertSame($expected, Escape::likeAny($input));
    }
}
