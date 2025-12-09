<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\caseStatement;
use function Latitude\QueryBuilder\field;
use function Latitude\QueryBuilder\literal;
use function Latitude\QueryBuilder\param;

class CaseStatementTest extends TestCase
{
    public function testBetween(): void
    {
        $field = field('role');

        $expr = caseStatement(
            $field->eq('admin'),
            literal(1)
        )
            ->when(
                $field->eq('editor'),
                literal(2),
            )
            ->when(
                $field->eq('user'),
                param(3),
            )
            ->else(param(4));

        $this->assertSql('CASE WHEN role = ? THEN 1 WHEN role = ? THEN 2 WHEN role = ? THEN ? ELSE ? END', $expr);
        $this->assertParams(['admin', 'editor', 'user', 3, 4], $expr);
    }
}
