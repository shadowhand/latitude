<?php

namespace Latitude\QueryBuilder\Query\MySql;

use Latitude\QueryBuilder\Engine;
use Latitude\QueryBuilder\EngineInterface;

trait MySqlEngineSetup
{
    protected function getEngine(): EngineInterface
    {
        return new Engine\MySqlEngine();
    }
}
