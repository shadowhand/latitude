<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

final class CaseStatement implements StatementInterface
{
    private ExpressionInterface $expression;
    private ?ExpressionInterface $else = null;

    public function __construct(?StatementInterface $expression = null)
    {
        $this->expression = new Expression('CASE');

        if ($expression) {
            $this->expression = $this->expression->append('%s', $expression);
        }
    }

    public function when(StatementInterface $when, StatementInterface $then): self
    {
        $this->expression = $this->expression->append('WHEN %s THEN %s', $when, $then);

        return $this;
    }

    public function else(StatementInterface $else): self
    {
        $this->else = new Expression('ELSE %s', $else);

        return $this;
    }

    public function sql(EngineInterface $engine): string
    {
        return $this->buildCaseStatement()->append('END')->sql($engine);
    }

    public function params(EngineInterface $engine): array
    {
        return $this->buildCaseStatement()->params($engine);
    }

    private function buildCaseStatement(): ExpressionInterface
    {
        $expression = $this->expression;

        if ($this->else) {
            $expression = $expression->append('%s', $this->else);
        }

        return $expression;
    }
}
