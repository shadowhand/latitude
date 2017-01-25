<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

trait CanEscapeIdentifiers
{
    protected function escapeIdentifier(string $identifier): string
    {
        return $identifier;
    }

    protected function escapeIdentifiers(array $identifiers): string
    {
        return \implode(', ', \array_map([$this, 'escapeIdentifier'], $identifiers));
    }
}
