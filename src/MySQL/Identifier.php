<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\MySQL;

use Latitude\QueryBuilder\Identifier as Base;

/**
 * Class Identifier
 * @package Latitude\QueryBuilder\MySQL
 */
class Identifier extends Base
{
    protected function surround(string $identifier): string
    {
        return "`$identifier`";
    }
}
