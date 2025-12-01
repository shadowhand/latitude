<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Sqlite;

use Latitude\QueryBuilder\Engine;
use Latitude\QueryBuilder\EngineInterface;

trait SqliteEngineSetup
{
    protected function getEngine(): EngineInterface
    {
        return new Engine\SqliteEngine();
    }
}
