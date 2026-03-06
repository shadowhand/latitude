<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Engine\BasicEngine;
use Latitude\QueryBuilder\EngineInterface;

class BasicQueryFactory implements QueryFactoryInterface
{
    use HasQueryFactoryMethods;

    protected EngineInterface $engine;

    public function __construct(?EngineInterface $engine = null)
    {
        $this->engine = $engine ?? new BasicEngine();
    }

    protected function getEngine(): EngineInterface
    {
        return $this->engine;
    }
}
