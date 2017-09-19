<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

use Iterator;
use Latitude\QueryBuilder\Identifier;

trait CanOrderBy
{
    public function orderBy(array ...$sorting): self
    {
        $this->orderBy = $sorting ?: null;
        return $this;
    }

    protected function orderByAsSql(Identifier $identifier): string
    {
        return sprintf('ORDER BY %s', $this->stringifyIterator($this->generateOrderBy($identifier)));
    }

    /**
     * Generate a list of ORDER BY statements.
     */
    protected function generateOrderBy(Identifier $identifier): Iterator
    {
        foreach ($this->orderBy as $sort) {
            if (empty($sort[1])) {
                yield $identifier->escapeQualified($sort[0]);
            } else {
                yield $identifier->escapeQualified($sort[0]) . ' ' . \strtoupper($sort[1]);
            }
        }
    }

    /**
     * @var array
     */
    protected $orderBy;
}
