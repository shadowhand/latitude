<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query\Firebird;

use Latitude\QueryBuilder\Engine;
use Latitude\QueryBuilder\EngineInterface;

trait FirebirdEngineSetup
{
    protected function getEngine(): EngineInterface
    {
        return new Engine\FirebirdEngine();
    }
}
