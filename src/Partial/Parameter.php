<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;

final class Parameter implements StatementInterface
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function sql(EngineInterface $engine): string
    {
        return '?';
    }

    public function params(EngineInterface $engine): array
    {
        return [$this->value];
    }
}
