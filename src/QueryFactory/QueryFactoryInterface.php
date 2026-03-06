<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Query;
use Latitude\QueryBuilder\StatementInterface;

interface QueryFactoryInterface
{
    /**
     * Create a new SELECT query
     *
     * @param string|StatementInterface ...$columns
     */
    public function select(...$columns): Query\SelectQuery;
    
    /**
     * Create a new SELECT DISTINCT query
     *
     * @param string|StatementInterface ...$columns
     */
    public function selectDistinct(...$columns): Query\SelectQuery;
    
    /**
     * Create a new INSERT query
     *
     * @param string|StatementInterface $table
     */
    public function insert($table, array $map = []): Query\InsertQuery;
    
    /**
     * Create a new DELETE query
     *
     * @param string|StatementInterface $table
     */
    public function delete($table): Query\DeleteQuery;
    
    /**
     * Create a new UPDATE query
     *
     * @param string|StatementInterface $table
     */
    public function update($table, array $map = []): Query\UpdateQuery;
    
}
