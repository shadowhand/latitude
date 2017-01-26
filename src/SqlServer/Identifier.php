<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\SqlServer;

use Latitude\QueryBuilder\Identifier as Base;

class Identifier extends Base
{
    public function escape(string $identifier): string
    {
        $this->guardIdentifier($identifier);
        return "[$identifier]";
    }
}
