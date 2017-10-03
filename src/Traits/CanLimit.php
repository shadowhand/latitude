<?php

namespace Latitude\QueryBuilder\Traits;

trait CanLimit
{
    public function limit($limit = null)
    {
        $this->limit = $limit;
        return $this;
    }
    protected function limitAsSql()
    {
        return sprintf('LIMIT %d', $this->limit);
    }
    /**
     * @var int
     */
    protected $limit;
}