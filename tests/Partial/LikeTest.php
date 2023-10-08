<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Partial;

use Latitude\QueryBuilder\TestCase;

use function Latitude\QueryBuilder\search;

class LikeTest extends TestCase
{
    public function testBegins(): void
    {
        $expr = search('username')->begins('sal');

        $this->assertSql('username LIKE ?', $expr);
        $this->assertParams(['sal%'], $expr);

        $expr = search('username')->notBegins('kim');

        $this->assertSql('username NOT LIKE ?', $expr);
        $this->assertParams(['kim%'], $expr);

        $expr = search('username')->begins('sal', false);

        $this->assertSql('username ILIKE ?', $expr);
        $this->assertParams(['sal%'], $expr);

        $expr = search('username')->notBegins('kim', false);

        $this->assertSql('username NOT ILIKE ?', $expr);
        $this->assertParams(['kim%'], $expr);
    }

    public function testContains(): void
    {
        $expr = search('username')->contains('ill');

        $this->assertSql('username LIKE ?', $expr);
        $this->assertParams(['%ill%'], $expr);

        $expr = search('username')->notContains('ar');

        $this->assertSql('username NOT LIKE ?', $expr);
        $this->assertParams(['%ar%'], $expr);

        $expr = search('username')->contains('ill', false);

        $this->assertSql('username ILIKE ?', $expr);
        $this->assertParams(['%ill%'], $expr);

        $expr = search('username')->notContains('ar', false);

        $this->assertSql('username NOT ILIKE ?', $expr);
        $this->assertParams(['%ar%'], $expr);
    }

    public function testEnds(): void
    {
        $expr = search('username')->ends('ly');

        $this->assertSql('username LIKE ?', $expr);
        $this->assertParams(['%ly'], $expr);

        $expr = search('username')->notEnds('am');

        $this->assertSql('username NOT LIKE ?', $expr);
        $this->assertParams(['%am'], $expr);

        $expr = search('username')->ends('ly', false);

        $this->assertSql('username ILIKE ?', $expr);
        $this->assertParams(['%ly'], $expr);

        $expr = search('username')->notEnds('am', false);

        $this->assertSql('username NOT ILIKE ?', $expr);
        $this->assertParams(['%am'], $expr);
    }
}
