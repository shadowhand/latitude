<?php

namespace Latitude\QueryBuilder\Postgres;

use Latitude\QueryBuilder\InsertQuery as Query;
class InsertQuery extends Query
{
    use Traits\CanReturnAfterExecute;
}