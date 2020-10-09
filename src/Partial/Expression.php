<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function array_map;
use function sprintf;
use function vsprintf;

final class Expression implements ExpressionInterface
{
    private string $pattern;
    private array $replacements;

    public function __construct(string $pattern, StatementInterface ...$replacements)
    {
        $this->pattern = $pattern;
        $this->replacements = $replacements;
    }

    public function append(string $pattern, StatementInterface ...$replacements): ExpressionInterface
    {
        $pattern = sprintf('%s %s', $this->pattern, $pattern);
        $replacements = [...$this->replacements, ...$replacements];

        return new self($pattern, ...$replacements);
    }

    public function sql(EngineInterface $engine): string
    {
        return vsprintf($this->pattern, array_map($engine->extractSql(), $this->replacements));
    }

    public function params(EngineInterface $engine): array
    {
        return $engine->flattenParams(...$this->replacements);
    }
}
