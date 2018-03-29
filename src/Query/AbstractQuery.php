<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\QueryInterface;

abstract class AbstractQuery implements QueryInterface
{
    use Capability\CanExpress;

    /** @var EngineInterface */
    protected $engine;

    final public function __construct(
        EngineInterface $engine
    ) {
        $this->engine = $engine;
    }
}
