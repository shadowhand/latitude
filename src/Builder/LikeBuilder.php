<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Builder;

use Latitude\QueryBuilder\CriteriaInterface;
use Latitude\QueryBuilder\Partial\LikeBegins;
use Latitude\QueryBuilder\Partial\LikeContains;
use Latitude\QueryBuilder\Partial\LikeEnds;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\criteria;

class LikeBuilder
{
    private StatementInterface $statement;

    public function __construct(StatementInterface $statement)
    {
        $this->statement = $statement;
    }

    public function begins(string $value, bool $caseSensitive = true): CriteriaInterface
    {
        return $this->like(new LikeBegins($value), $caseSensitive);
    }

    public function notBegins(string $value, bool $caseSensitive = true): CriteriaInterface
    {
        return $this->notLike(new LikeBegins($value), $caseSensitive);
    }

    public function contains(string $value, bool $caseSensitive = true): CriteriaInterface
    {
        return $this->like(new LikeContains($value), $caseSensitive);
    }

    public function notContains(string $value, bool $caseSensitive = true): CriteriaInterface
    {
        return $this->notLike(new LikeContains($value), $caseSensitive);
    }

    public function ends(string $value, bool $caseSensitive = true): CriteriaInterface
    {
        return $this->like(new LikeEnds($value), $caseSensitive);
    }

    public function notEnds(string $value, bool $caseSensitive = true): CriteriaInterface
    {
        return $this->notLike(new LikeEnds($value), $caseSensitive);
    }

    protected function like(StatementInterface $value, bool $caseSensitive = true): CriteriaInterface
    {
        return criteria($caseSensitive ? '%s LIKE %s' : '%s ILIKE %s', $this->statement, $value);
    }

    protected function notLike(StatementInterface $value, bool $caseSensitive = true): CriteriaInterface
    {
        return criteria($caseSensitive ? '%s NOT LIKE %s' : '%s NOT ILIKE %s', $this->statement, $value);
    }
}
