<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Postgres;

use Latitude\QueryBuilder\DeleteQuery as Query;

class DeleteQuery extends Query
{
    use Traits\CanReturnAfterExecute;
}
