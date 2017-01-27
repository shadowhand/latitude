<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Eloquent\Liberator\Liberator;
use PHPUnit_Framework_TestCase as TestCase;

class IdentifierTest extends TestCase
{
    /**
     * @var Identifier
     */
    private $identifier;

    public function setUp()
    {
        $this->identifier = Identifier::make();
    }

    public function testExpression()
    {
        $expr = Expression::make('COUNT(*) AS %s', 'total');

        $this->assertSame('COUNT(*) AS total', $expr->sql($this->identifier));

        $expr = Expression::make('COUNT(DISTINCT %s) AS %s', 'id', 'total');

        $this->assertSame('COUNT(DISTINCT id) AS total', $expr->sql($this->identifier));
    }

    public function testAlias()
    {
        $aliases = [
            'id userId',
            'id as userId',
            'id AS userId',
        ];

        foreach ($aliases as $alias) {
            $this->assertSame('id AS userId', $this->identifier->escapeAlias($alias));
        }

        $this->assertSame('users.id AS userId', $this->identifier->escapeAlias('users.id userId'));
    }

    public function testAll()
    {
        $identifiers = [
            'users',
        ];

        $this->assertSame(
            \array_map([$this->identifier, 'escape'], $identifiers),
            $this->identifier->all($identifiers)
        );
    }

    public function testAllQualified()
    {
        $identifiers = [
            'users',
            'users.id',
        ];

        $this->assertSame(
            \array_map([$this->identifier, 'escapeQualified'], $identifiers),
            $this->identifier->allQualified($identifiers)
        );
    }

    public function testAllAliases()
    {
        $identifiers = [
            'users u',
            'users.id userId',
        ];

        $this->assertSame(
            \array_map([$this->identifier, 'escapeAlias'], $identifiers),
            $this->identifier->allAliases($identifiers)
        );
    }

    /**
     * @dataProvider dataInvalid
     */
    public function testInvalid(string $invalid)
    {
        $this->expectException(IdentifierException::class);
        $this->expectExceptionCode(IdentifierException::INVALID_IDENTIFIER);

        $this->identifier->escape($invalid);
    }

    public function dataInvalid()
    {
        return [
            'cannot start with a digit' => ['0col'],
            'cannot contain invalid characters' => ['bad!'],
        ];
    }

    public function testDefault()
    {
        // No default, should use base class
        $this->assertSame(Identifier::class, get_class(Identifier::getDefault()));

        // No default, should not be the same twice
        $this->assertNotSame(Identifier::getDefault(), Identifier::getDefault());

        // Set default, should be the same twice
        $default = Identifier::make();
        $this->assertNull(Identifier::setDefault($default));
        $this->assertSame($default, Identifier::getDefault());

        // Clear default
        $identifier = Liberator::liberateClassStatic(Identifier::class);
        $identifier::liberator()->default = null;

        // Verify cleared
        $this->assertNotSame(Identifier::getDefault(), Identifier::getDefault());
    }
}
