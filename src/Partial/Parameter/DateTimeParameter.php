<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial\Parameter;

use DateTimeInterface;
use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;

final class DateTimeParameter implements StatementInterface
{
    private DateTimeInterface $value;

    public function __construct(DateTimeInterface $value)
    {
        $this->value = $value;
    }

    public function sql(EngineInterface $engine): string
    {
        return '?';
    }

    public function params(EngineInterface $engine): array
    {
        return [$engine->exportParameter($this->value)];
    }
}
