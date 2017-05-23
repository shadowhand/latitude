<?php

namespace Latitude\QueryBuilder;

use PHPUnit\Framework\TestCase;
class LikeValueTest extends TestCase
{
    public function testEscape()
    {
        $input = 'string_not%escaped';
        $expected = 'string\\_not\\%escaped';
        $this->assertSame($expected, LikeValue::escape($input));
    }
    public function testLikeAny()
    {
        $input = 'a % string';
        $expected = '%a \\% string%';
        $this->assertSame($expected, LikeValue::any($input));
    }
}