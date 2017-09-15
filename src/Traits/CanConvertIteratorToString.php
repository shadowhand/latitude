<?php

namespace Latitude\QueryBuilder\Traits;

use Iterator;
trait CanConvertIteratorToString
{
    /**
     * Convert an iterator to a string.
     */
    protected function stringifyIterator(Iterator $iterator, $bind = ', ')
    {
        return \implode($bind, \iterator_to_array($iterator));
    }
}