<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

final class Query
{
    /** @var string */
    private $sql;

    /** @var array */
    private $params;

    public function __construct(
        string $sql,
        array $params
    ) {
        $this->sql = $sql;
        $this->params = $params;
    }

    public function sql(): string
    {
        return $this->sql;
    }

    public function params(): array
    {
        return $this->params;
    }
}
