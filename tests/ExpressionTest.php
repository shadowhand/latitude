<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use PHPUnit_Framework_TestCase as TestCase;

class ExpressionTest extends TestCase
{
    public function testExpression()
    {
        $expression = Expression::make('COUNT(*) AS %s', 'total');

        $this->assertSame('COUNT(*) AS total', $expression->sql());
    }
}
