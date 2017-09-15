<?php
namespace Latitude\QueryBuilder\MySQL;

use Latitude\QueryBuilder\DeleteQuery as Query;
use Latitude\QueryBuilder\Identifier;
use Latitude\QueryBuilder\Traits\CanConvertIteratorToString;
use Latitude\QueryBuilder\Traits\CanLimit;
use Latitude\QueryBuilder\Traits\CanOrderBy;

class DeleteQuery extends Query
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