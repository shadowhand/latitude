<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Traits;

use Latitude\QueryBuilder\Expression;

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

        if ($value === true) {
            unset($this->params[$index]);
            return 'TRUE';
        }

        if ($value === false) {
            unset($this->params[$index]);
            return 'FALSE';
        }

        if ($value === null) {
            unset($this->params[$index]);
            return 'NULL';
        }

        if ($value instanceof Expression) {
            unset($this->params[$index]);
            return $value->sql();
        }

        return '?';
    }
}
