<?php

namespace Latitude\QueryBuilder;

interface Statement
{
    /**
     * Get the SQL statement of the query.
     */
    public function sql(Identifier $identifier = null);
    /**
     * Get the SQL parameters of the query.
     */
    public function params();
}