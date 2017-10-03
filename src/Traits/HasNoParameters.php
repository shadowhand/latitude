<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

trait HasNoParameters
{
    // Statement
    public function params(): array
    {
        return [];
    }
}
