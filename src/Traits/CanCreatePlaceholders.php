<?php

namespace Latitude\QueryBuilder\Traits;

trait CanCreatePlaceholders
{
    protected function createPlaceholders($count)
    {
        return '?' . \str_repeat(', ?', $count - 1);
    }
}