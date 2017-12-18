<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Common;

use Latitude\QueryBuilder\Identifier as Base;

/**
 * Class Identifier
 * @package Latitude\QueryBuilder\Common
 */
class Identifier extends Base
{
    protected function surround(string $identifier): string
    {
        return "\"$identifier\"";
    }
}
