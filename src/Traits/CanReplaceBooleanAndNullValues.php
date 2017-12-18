<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

use Latitude\QueryBuilder\Expression;

/**
 * Trait CanReplaceBooleanAndNullValues
 * @package Latitude\QueryBuilder\Traits
 */
trait CanReplaceBooleanAndNullValues
{
    /**
     * Get a placeholder or string equivalent for null or boolean values.
     *
     * PDO treats indexed parameters as strings when the type is not bound.
     * This will fail for null and boolean values. By replacing the values
     * directly more consistent queries can be built.
     */
    protected function placeholderValue(int $index): string
    {
        $value = $this->params[$index];

        if ($this->isPlaceholderValue($value)) {
            return '?';
        }

        if ($value instanceof Expression) {
            return $value->sql();
        }

        // null -> "NULL", true -> "TRUE", etc
        return \strtoupper(\var_export($value, true));
    }

    /**
     * Determine if a value can be represented by a placeholder.
     * @param mixed $value
     * @return bool
     */
    protected function isPlaceholderValue($value): bool
    {
        if (\in_array($value, [true, false, null], true)) {
            return false;
        }

        if ($value instanceof Expression) {
            return false;
        }

        return true;
    }

    /**
     * Get all parameters that can be placeholders.
     */
    protected function placeholderParams(): array
    {
        return \array_values(
            \array_filter($this->params,
                /**
                 * @param mixed $value
                 * @return bool
                 */
                function ($value): bool {
                    return $this->isPlaceholderValue($value);
                }
            )
        );
    }
}
