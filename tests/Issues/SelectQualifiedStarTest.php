<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder\Issues;

use Latitude\QueryBuilder\Query\MySql\MySqlEngineSetup;
use Latitude\QueryBuilder\TestCase;

class SelectQualifiedStarTest extends TestCase
{
    use MySqlEngineSetup;

    public function testSelectQualifiedStar(): void
    {
        $query = $this->factory->select('a.*', 'b.id', 'b.name')->from('tests');

        $this->assertSql('SELECT `a`.*, `b`.`id`, `b`.`name` FROM `tests`', $query);
    }
}
