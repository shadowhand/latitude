<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\SqlServer;

use Latitude\QueryBuilder\LikeValue as Base;

abstract class LikeValue extends Base
{
    public static function escape(string $value): string
    {
        $value = parent::escape($value);

        // MSSQL also includes character ranges.
        $value = str_replace('[', '\\[', $value);
        $value = str_replace(']', '\\]', $value);

        return $value;
    }
}
