<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Countable;

class InValue implements Countable
{
    /**
     * Create a new IN value wrapper.
     */
    public static function make(array $values): InValue
    {
        return new static($values);
    }

    public function values(): array
    {
        return $this->values;
    }

    // Countable
    public function count(): int
    {
        return \count($this->values);
    }

    /**
     * @var array
     */
    protected $values;

    protected function __construct(array $values)
    {
        $this->values = $values;
    }
}
