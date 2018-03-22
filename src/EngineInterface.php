<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

interface EngineInterface
{
    /**
     * Create a new SELECT query
     *
     * @param string|StatementInterface ...$columns
     */
    public function select(...$columns): Query\SelectQuery;

    /**
     * Create a new INSERT query
     *
     * @param string|StatementInterface $table
     */
    public function insert($table, array $map = []): Query\InsertQuery;

    /**
     * Escape a single identifier
     */
    public function escapeIdentifier(string $identifier): string;

    /**
     * Escape a like value
     */
    public function escapeLike(string $parameter): string;

    /**
     * Get a function to extract parameters from statements
     *
     * Signature: function (StatementInterface $statement): array
     */
    public function extractParams(): callable;

    /**
     * Get a function to extract SQL from statements
     *
     * Signature: function (StatementInterface $statement): string
     */
    public function extractSql(): callable;

    /**
     * Flatten all parameters from multiple statements
     */
    public function flattenParams(StatementInterface ...$statements): array;

    /**
     * Flatten all SQL from multiple statements
     */
    public function flattenSql(string $separator = ' ', StatementInterface ...$statements): string;
}
