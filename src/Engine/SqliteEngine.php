<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

class SqliteEngine extends BasicEngine
{
    public function getSqlParamValue($value): string
    {
        if (is_bool($value)) {
            return (string) (int) $value;
        }

        return parent::getSqlParamValue($value);
    }
}
