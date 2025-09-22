<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use Latitude\QueryBuilder\Query;

use function sprintf;
use function str_replace;

class SqlServerEngine extends BasicEngine
{
    public function makeSelect(): Query\SelectQuery
    {
        return new Query\SqlServer\SelectQuery($this);
    }

    public function makeDelete(): Query\DeleteQuery
    {
        return new Query\SqlServer\DeleteQuery($this);
    }

    public function escapeIdentifier(string $identifier): string
    {
        return sprintf('[%s]', str_replace(']', ']]', $identifier));
    }

    public function escapeLike(string $parameter): string
    {
        // MSSQL also includes character ranges.
        return str_replace(['[', ']'], ['\\[', '\\]'], parent::escapeLike($parameter));
    }
}
