<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial\Parameter;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;

final class BoolParameter implements StatementInterface
{
    private bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function sql(EngineInterface $engine): string
    {
        return $engine->exportParameter($this->value);
    }

    public function params(EngineInterface $engine): array
    {
        return [];
    }
}
