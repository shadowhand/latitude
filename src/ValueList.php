<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Countable;
use Iterator;

class ValueList implements
    Countable,
    Statement
{
    use Traits\CanConvertIteratorToString;
    use Traits\CanReplaceBooleanAndNullValues;

    /**
     * Create a new value list.
     */
    public static function make(array $params): ValueList
    {
        $values = new static();
        $values->params = array_values($params);
        return $values;
    }

    // Countable
    public function count()
    {
        return \count($this->params);
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
