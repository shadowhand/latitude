<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;

final class Literal implements StatementInterface
{
    /** @var mixed */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function sql(EngineInterface $engine): string
    {
        return (string) $this->value;
    }

    public function params(EngineInterface $engine): array
    {
        return [];
    }
}
