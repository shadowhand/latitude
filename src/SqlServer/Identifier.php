<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\SqlServer;

use Latitude\QueryBuilder\Identifier as Base;

class Identifier extends Base
{
    protected function surround(string $identifier): string
    {
        return "[$identifier]";
    }
}
