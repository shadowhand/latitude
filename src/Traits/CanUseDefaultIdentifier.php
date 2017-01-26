<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

use Latitude\QueryBuilder\Identifier;

trait CanUseDefaultIdentifier
{
    protected function getDefaultIdentifier(Identifier $identifier = null): Identifier
    {
        if ($identifier) {
            return $identifier;
        }

        return Identifier::getDefault();
    }
}
