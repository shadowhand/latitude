<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use function sprintf;

class CommonEngine extends BasicEngine
{
    public function escapeIdentifier(string $identifier): string
    {
        return sprintf('"%s"', $identifier);
    }
}
