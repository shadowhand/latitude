<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use function sprintf;
use function str_replace;

class CommonEngine extends BasicEngine
{
    public function escapeIdentifier(string $identifier): string
    {
        return sprintf('"%s"', str_replace('"', '""', $identifier));
    }
}
