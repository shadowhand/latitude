<?php

namespace Latitude\QueryBuilder\Traits;

use Latitude\QueryBuilder\Identifier;
trait CanUseDefaultIdentifier
{
    protected function getDefaultIdentifier(Identifier $identifier = null)
    {
        if ($identifier) {
            return $identifier;
        }
        return Identifier::getDefault();
    }
}