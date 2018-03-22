<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;

class DeleteQuery implements StatementInterface
{
    use Capability\CanExpress;
    use Capability\HasFrom;
    use Capability\HasWhere;

    /** @var EngineInterface */
    private $engine;

    public function __construct(
        EngineInterface $engine
    ) {
        $this->engine = $engine;
    }

    public function asExpression(): ExpressionInterface
    {
        $query = express('DELETE');
        $query = $this->applyFrom($query);
        $query = $this->applyWhere($query);
        return $query;
    }
}
