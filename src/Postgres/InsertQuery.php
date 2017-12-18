<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Postgres;

use Latitude\QueryBuilder\InsertQuery as Query;

/**
 * Class InsertQuery
 * @package Latitude\QueryBuilder\Postgres
 */
class InsertQuery extends Query
{
    use Traits\CanReturnAfterExecute;
}
