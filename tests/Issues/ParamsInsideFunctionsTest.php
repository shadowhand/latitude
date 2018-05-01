<?php

namespace Latitude\QueryBuilder\Issues;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\fn;
use function Latitude\QueryBuilder\param;

/**
 * @link https://github.com/shadowhand/latitude/issues/57
 */
class ParamsInsideFunctionsTest extends TestCase
{
    public function testFnColumns()
    {
        $expr = fn('COUNT', 'id');

        $this->assertSql('COUNT(id)', $expr);
        $this->assertParams([], $expr);

    }

    public function testFnParams()
    {
        $expr = fn('POINT', param(1), param(2));

        $this->assertSql('POINT(?, ?)', $expr);
        $this->assertParams([1, 2], $expr);
    }
}
