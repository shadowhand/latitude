<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

class CommonEngine extends BasicEngine
{
    public function escapeIdentifier(string $identifier): string
    {
        return "\"$identifier\"";
    }
}
