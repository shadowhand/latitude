<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use function is_bool;

class SqliteEngine extends BasicEngine
{
    /**
     * @inheritDoc
     */
    public function exportParameter($param): string
    {
        if (is_bool($param)) {
            // SQLite does not have a boolean storage class, so we use 1/0 instead of true/false.
            return (string) (int) $param;
        }

        return parent::exportParameter($param);
    }
}
