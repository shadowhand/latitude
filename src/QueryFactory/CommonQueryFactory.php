<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Engine\CommonEngine;
use Latitude\QueryBuilder\EngineInterface;

class CommonQueryFactory implements QueryFactoryInterface
{
    use HasQueryFactoryMethods;

    protected EngineInterface $engine;

    public function __construct(?CommonEngine $engine = null)
    {
        $this->engine = $engine ?? new CommonEngine();
    }

    protected function getEngine(): EngineInterface
    {
        return $this->engine;
    }
}
