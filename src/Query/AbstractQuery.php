<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;
use Latitude\QueryBuilder\QueryInterface;
use Latitude\QueryBuilder\QueryWithNamedParams;

abstract class AbstractQuery implements QueryInterface
{
    protected EngineInterface $engine;

    public function __construct(
        EngineInterface $engine
    ) {
        $this->engine = $engine;
    }

    abstract public function asExpression(): ExpressionInterface;

    abstract protected function startExpression(): ExpressionInterface;

    public function compile(): Query
    {
        $query = $this->asExpression();

        return new Query(
            $query->sql($this->engine),
            $query->params($this->engine)
        );
    }

    public function sql(EngineInterface $engine): string
    {
        return $this->asExpression()->sql($engine);
    }

    public function params(EngineInterface $engine): array
    {
        return $this->asExpression()->params($engine);
    }

    public function toQueryWithNamedParams(EngineInterface $engine, string $template = QueryWithNamedParams::DEFAULT_PARAM_NAME_TEMPLATE): QueryWithNamedParams
    {
        return new QueryWithNamedParams(
            $this->sql($engine),
            $this->params($engine),
            $template
        );
    }
}
