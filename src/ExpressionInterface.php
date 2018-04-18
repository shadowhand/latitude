<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

interface ExpressionInterface extends StatementInterface
{
    /**
     * Create a new expression with additional replacements
     */
    public function append(string $pattern, StatementInterface ...$replacements): ExpressionInterface;
}
