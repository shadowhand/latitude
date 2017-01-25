<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

trait CanCreatePlaceholders
{
    protected function createPlaceholders(int $count): string
    {
        return '?' . \str_repeat(', ?', $count - 1);
    }
}
