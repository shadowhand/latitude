<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

use Latitude\QueryBuilder\Identifier;

/**
 * Trait CanUseDefaultIdentifier
 * @package Latitude\QueryBuilder\Traits
 */
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
