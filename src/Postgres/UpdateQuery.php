<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Postgres;

use Latitude\QueryBuilder\UpdateQuery as Query;

/**
 * Class UpdateQuery
 * @package Latitude\QueryBuilder\Postgres
 */
class UpdateQuery extends Query
{
    use Traits\CanReturnAfterExecute;
}
