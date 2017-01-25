<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Postgres;

use Latitude\QueryBuilder\UpdateQuery as Query;

class UpdateQuery extends Query
{
    use Traits\CanReturnAfterExecute;
}
