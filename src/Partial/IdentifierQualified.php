<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;

final class IdentifierQualified implements StatementInterface
{
    /** @var StatementInterface[] */
    private array $identifiers;

    public function __construct(
        StatementInterface ...$identifiers
    ) {
        $this->identifiers = $identifiers;
    }

    public function sql(EngineInterface $engine): string
    {
        return $engine->flattenSql('.', ...$this->identifiers);
    }

    public function params(EngineInterface $engine): array
    {
        return $engine->flattenParams(...$this->identifiers);
    }
}
