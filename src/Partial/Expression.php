<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

final class Expression implements ExpressionInterface
{
    /** @var string */
    private $pattern;

    /** @var StatementInterface[] */
    private $replacements;

    public function __construct(
        string $pattern,
        StatementInterface ...$replacements
    ) {
        $this->pattern = $pattern;
        $this->replacements = $replacements;
    }

    public function append(string $pattern, StatementInterface ...$replacements): ExpressionInterface
    {
        return new self("{$this->pattern} $pattern", ...array_merge($this->replacements, $replacements));
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
