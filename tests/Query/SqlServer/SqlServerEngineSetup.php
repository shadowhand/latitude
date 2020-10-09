<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\SqlServer;

use Latitude\QueryBuilder\Engine;
use Latitude\QueryBuilder\EngineInterface;

trait SqlServerEngineSetup
{
    protected function getEngine(): EngineInterface
    {
        return new Engine\SqlServerEngine();
    }
}
