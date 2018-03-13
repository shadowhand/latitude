<?php

namespace Latitude\QueryBuilder\Query\Postgres;

use Latitude\QueryBuilder\Engine\PostgresEngine;

trait PostgresEngineSetup
{
    public function setUp()
    {
        $this->engine = new PostgresEngine();
    }
}
