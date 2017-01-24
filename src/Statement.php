<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

interface Statement
{
    /**
     * Get the SQL statement of the query.
     */
    public function sql(): string;

    /**
     * Get the SQL parameters of the query.
     */
    public function params(): array;
}
