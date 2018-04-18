<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

interface StatementInterface
{
    public function sql(EngineInterface $engine): string;

    public function params(EngineInterface $engine): array;
}
