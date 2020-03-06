<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

interface EngineInterface
{
    /**
     * Create a new SELECT query
     */
    public function makeSelect(): Query\SelectQuery;

    /**
     * Create a new INSERT query
     */
    public function makeInsert(): Query\InsertQuery;

    /**
     * Create a new UPDATE query
     */
    public function makeUpdate(): Query\UpdateQuery;

    /**
     * Create a new DELETE query
     */
    public function makeDelete(): Query\DeleteQuery;

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

    /**
     * Export a query parameter that may need engine-specific formatting
     */
    public function exportParameter($param): string;
}
