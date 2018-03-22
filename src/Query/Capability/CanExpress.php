<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Capability;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;

trait CanExpress
{
    abstract public function asExpression(): ExpressionInterface;

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
}
