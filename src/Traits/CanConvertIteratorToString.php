<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

use Iterator;

/**
 * Trait CanConvertIteratorToString
 * @package Latitude\QueryBuilder\Traits
 */
trait CanConvertIteratorToString
{
    /**
     * Convert an iterator to a string.
     */
    protected function stringifyIterator(Iterator $iterator, string $bind = ', '): string
    {
        return \implode($bind, \iterator_to_array($iterator));
    }
}
