<?php

namespace Latitude\QueryBuilder\MySQL;

use Latitude\QueryBuilder\Identifier as Base;
class Identifier extends Base
{
    protected function surround($identifier)
    {
        return "`{$identifier}`";
    }
}