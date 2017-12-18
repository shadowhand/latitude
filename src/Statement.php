<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

/**
 * Interface Statement
 * @package Latitude\QueryBuilder
 */
interface Statement
{
    /**
     * Get the SQL statement of the query.
     *
     * @param Identifier|null $identifier
     * @return string
     */
    public function sql(Identifier $identifier = null): string;

    /**
     * Get the SQL parameters of the query.
     *
     * @return array
     */
    public function params(): array;
}
