<?php

namespace Latitude\QueryBuilder\Common;

use Latitude\QueryBuilder\Identifier as Base;
class Identifier extends Base
{
    protected function surround($identifier)
    {
        return "\"{$identifier}\"";
    }
}