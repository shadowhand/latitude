<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\SqlServer;

use Latitude\QueryBuilder\Escape as Base;

abstract class Escape extends Base
{
    public static function like(string $value): string
    {
        $value = parent::like($value);

        // MSSQL also includes character ranges.
        $value = str_replace('[', '\\[', $value);
        $value = str_replace(']', '\\]', $value);

        return $value;
    }
}
