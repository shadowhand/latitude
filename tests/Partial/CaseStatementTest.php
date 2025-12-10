<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\caseStatement;
use function Latitude\QueryBuilder\field;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\literal;
use function Latitude\QueryBuilder\param;

class CaseStatementTest extends TestCase
{
    public function testAsIfElse(): void
    {
        $field = field('role');

        $expr = caseStatement()
            ->when(
                $field->eq('admin'),
                literal(1),
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

    public function testAsSwitch(): void
    {
        $expr = caseStatement(identify('role'))
            ->when(
                param('admin'),
                literal(1),
            )
            ->when(
                param('editor'),
                literal(2),
            )
            ->when(
                param('user'),
                param(3),
            )
            ->else(param(4));

        $this->assertSql('CASE role WHEN ? THEN 1 WHEN ? THEN 2 WHEN ? THEN ? ELSE ? END', $expr);
        $this->assertParams(['admin', 'editor', 'user', 3, 4], $expr);
    }
}
