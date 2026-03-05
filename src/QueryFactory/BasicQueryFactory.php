<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query;
use Latitude\QueryBuilder\QueryFactory\QueryFactoryInterface;

class BasicQueryFactory implements QueryFactoryInterface
{
    use GenericQueryFactoryMethods;
    
    protected EngineInterface $engine;
    
    public function __construct(?EngineInterface $engine = null)
    {
        $this->engine = $engine;
    }
    
    protected function getEngine(): EngineInterface
    {
        return $this->engine;
    }
}
