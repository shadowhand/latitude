<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial\Parameter;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;

final class NullParameter implements StatementInterface
{
    public function sql(EngineInterface $engine): string
    {
        return $engine->exportParameter(null);
    }

    public function params(EngineInterface $engine): array
    {
        return [];
    }
}
