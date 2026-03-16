<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\Query;

class FirebirdEngine extends CommonEngine
{
    public function makeSelect(): Query\SelectQuery
    {
        return new Query\Firebird\SelectQuery($this);
    }

    public function makeInsert(): Query\InsertQuery
    {
        return new Query\Firebird\InsertQuery($this);
    }

    public function makeDelete(): Query\DeleteQuery
    {
        return new Query\Firebird\DeleteQuery($this);
    }
}
