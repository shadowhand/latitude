<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\Query;

class MySqlEngine extends BasicEngine
{
    public function makeSelect(): Query\SelectQuery
    {
        return new Query\MySql\SelectQuery($this);
    }

    public function escapeIdentifier(string $identifier): string
    {
        return "`$identifier`";
    }
}
