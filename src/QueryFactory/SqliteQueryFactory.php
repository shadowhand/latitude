<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\QueryFactory;

use Latitude\QueryBuilder\Engine\SqliteEngine;
use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Query;

class SqliteQueryFactory implements QueryFactoryInterface
{
    use GenericQueryFactoryMethods;
    
    protected SqliteEngine $engine;
    
    public function __construct(?SqliteEngine $engine = null)
    {
        $this->engine = $engine ?? new SqliteEngine();
    }
    
    protected function getEngine(): SqliteEngine
    {
        return $this->engine;
    }
    
    public function insert($table, array $map = []): Query\Sqlite\InsertQuery
    {
        $query = $this->getEngine()->makeInsert()->into($table);
        
        if ($map) {
            $query = $query->map($map);
        }
        
        return $query;
    }
    
    public function update($table, array $map = []): Query\Sqlite\UpdateQuery
    {
        $query = $this->getEngine()->makeUpdate()->table($table);
        
        if ($map) {
            $query = $query->set($map);
        }
        
        return $query;
    }
}
