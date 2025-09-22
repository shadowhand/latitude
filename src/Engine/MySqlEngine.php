<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\Query;

use function sprintf;
use function str_replace;

class MySqlEngine extends BasicEngine
{
    public function makeSelect(): Query\SelectQuery
    {
        return new Query\MySql\SelectQuery($this);
    }

    public function makeInsert(): Query\InsertQuery
    {
        return new Query\MySql\InsertQuery($this);
    }

    public function escapeIdentifier(string $identifier): string
    {
        return sprintf('`%s`', str_replace('`', '``', $identifier));
    }
}
