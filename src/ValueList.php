<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Iterator;

class ValueList implements Statement
{
    use Traits\CanConvertIteratorToString;
    use Traits\CanReplaceBooleanAndNullValues;

    /**
     * Create a new value list.
     */
    public static function make(...$params)
    {
        $values = new static($params);
        $values->params = $params;
        return $values;
    }

    // Statement
    public function sql(Identifier $identifier = null): string
    {
        return '(' . $this->stringifyIterator($this->generatePlaceholders()) . ')';
    }

    // Statement
    public function params(): array
    {
        return $this->placeholderParams();
    }

    /**
     * @var array
     */
    protected $params;

    /**
     * Generate a placeholder.
     */
    protected function generatePlaceholders(): Iterator
    {
        foreach (\array_keys($this->params) as $index) {
            yield $this->placeholderValue($index);
        }
    }
}
