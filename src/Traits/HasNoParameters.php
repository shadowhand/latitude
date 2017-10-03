<?php

namespace Latitude\QueryBuilder\Traits;

trait HasNoParameters
{
    // Statement
    public function params()
    {
        return [];
    }
}