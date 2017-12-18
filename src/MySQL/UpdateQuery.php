<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\MySQL;

use Latitude\QueryBuilder\Identifier;
use Latitude\QueryBuilder\Traits\CanConvertIteratorToString;
use Latitude\QueryBuilder\Traits\CanLimit;
use Latitude\QueryBuilder\Traits\CanOrderBy;
use Latitude\QueryBuilder\UpdateQuery as Query;

/**
 * Class UpdateQuery
 * @package Latitude\QueryBuilder\MySQL
 */
class UpdateQuery extends Query
{
    use CanConvertIteratorToString;
    use CanLimit;
    use CanOrderBy;

    public function sql(Identifier $identifier = null): string
    {
        $identifier = $this->getDefaultIdentifier($identifier);

        $parts = [];

        if ($this->orderBy) {
            $parts[] = $this->orderByAsSql($identifier);
        }

        if (isset($this->limit)) {
            $parts[] = $this->limitAsSql();
        }

        $sql = parent::sql($identifier);

        if (!$parts) {
            return $sql;
        }

        return sprintf('%s %s', $sql, implode(' ', $parts));
    }
}
