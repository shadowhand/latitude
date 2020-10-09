<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\Partial\Parameter\BoolParameter;
use Latitude\QueryBuilder\Partial\Parameter\NullParameter;
use Latitude\QueryBuilder\StatementInterface;

use function is_bool;

final class Parameter implements StatementInterface
{
    /**
     * @param mixed $value
     */
    public static function create($value): StatementInterface
    {
        if ($value === null) {
            return new NullParameter();
        }

        if (is_bool($value)) {
            return new BoolParameter($value);
        }

        return new self($value);
    }

    private string $sql = '?';
    private array $params;

    /**
     * @param string|float|int $value
     */
    public function __construct($value)
    {
        $this->params = [$value];
    }

    public function sql(EngineInterface $engine): string
    {
        return $engine->exportParameter($this->sql);
    }

    public function params(EngineInterface $engine): array
    {
        return $this->params;
    }
}
