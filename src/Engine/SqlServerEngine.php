<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

class SqlServerEngine extends BasicEngine
{
    public function escapeIdentifier(string $identifier): string
    {
        return "[$identifier]";
    }

    public function escapeLike(string $parameter): string
    {
        // MSSQL also includes character ranges.
        return str_replace(['[', ']'], ['\\[', '\\]'], parent::escapeLike($parameter));
    }
}
