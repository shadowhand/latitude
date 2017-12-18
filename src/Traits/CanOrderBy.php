<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

use Iterator;
use Latitude\QueryBuilder\Identifier;

/**
 * Trait CanOrderBy
 * @package Latitude\QueryBuilder\Traits
 */
trait CanOrderBy
{
    /**
     * @param array[] ...$sorting
     * @return self
     */
    public function orderBy(array ...$sorting): self
    {
        $this->orderBy = $sorting;
        return $this;
    }

    /**
     * @param Identifier $identifier
     * @return string
     * @throws \TypeError
     */
    protected function orderByAsSql(Identifier $identifier): string
    {
        return sprintf('ORDER BY %s', $this->stringifyIterator($this->generateOrderBy($identifier)));
    }

    /**
     * Generate a list of ORDER BY statements.
     * @throws \TypeError
     */
    protected function generateOrderBy(Identifier $identifier): Iterator
    {
        if (\is_null($this->orderBy)) {
            throw new \TypeError('$this->orderBy is null');
        }
        foreach ($this->orderBy as $sort) {
            if (empty($sort[1])) {
                yield $identifier->escapeQualified($sort[0]);
            } else {
                yield $identifier->escapeQualified($sort[0]) . ' ' . \strtoupper($sort[1]);
            }
        }
    }

    /**
     * @var array|null
     */
    protected $orderBy = null;
}
