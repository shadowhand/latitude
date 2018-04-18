<?php

namespace Latitude\QueryBuilder\Query\Postgres;

use Latitude\QueryBuilder\Engine;
use Latitude\QueryBuilder\EngineInterface;

trait PostgresEngineSetup
{
    protected function getEngine(): EngineInterface
    {
        return new Engine\PostgresEngine();
    }
}
