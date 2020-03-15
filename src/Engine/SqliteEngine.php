<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

class SqliteEngine extends BasicEngine
{
    public function exportParameter($param): string
    {
        if (is_bool($param)) {
            // Convert boolean to stringified integer. SQLite does not have a separate boolean storage class.
            // Instead, boolean values are stored as integers 0 (false) and 1 (true).
            return (string) (int) $param;
        }

        return parent::exportParameter($param);
    }
}
