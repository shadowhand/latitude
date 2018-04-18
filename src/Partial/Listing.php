<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;

final class Listing implements StatementInterface
{
    /** @var string */
    private $separator;

    /** @var StatementInterface[] */
    private $statements;

    public function __construct(
        string $separator,
        StatementInterface ...$statements
    ) {
        $this->separator = $separator;
        $this->statements = $statements;
    }

    public function sql(EngineInterface $engine): string
    {
        return $engine->flattenSql($this->separator, ...$this->statements);
    }

    public function params(EngineInterface $engine): array
    {
        return $engine->flattenParams(...$this->statements);
    }
}
