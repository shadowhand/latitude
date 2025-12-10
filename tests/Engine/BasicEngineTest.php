<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Engine;

use DateTime;
use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\field;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\param;

class BasicEngineTest extends TestCase
{
    protected function setUp(): void
    {
        $this->engine = new BasicEngine();
    }

    public function testDateTime(): void
    {
        $dateTimeString = '2025-01-01 20:45:08';

        $dateTime = new DateTime($dateTimeString);

        $query = $this->engine->makeSelect()
            ->columns('id', 'name', 'created_at')
            ->from('users')
            ->where(field('created_at')->gte($dateTime))
            ->compile();

        $this->assertSame([$dateTimeString], $query->params());
    }
}
