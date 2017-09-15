<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

trait CanLimit
{
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    protected function limitAsSql(): string
    {
        return sprintf('LIMIT %d', $this->limit);
    }

    /**
     * @var int
     */
    protected $limit;
}
