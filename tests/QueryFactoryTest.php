<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Eloquent\Liberator\Liberator;
use PHPUnit_Framework_TestCase as TestCase;

class QueryFactoryTest extends TestCase
{
    public function tearDown()
    {
        // Clear default identifier
        $identifier = Liberator::liberateClassStatic(Identifier::class);
        $identifier::liberator()->default = null;
    }

    /**
     * @dataProvider dataFactory
     */
    public function testFactory(
        string $engine,
        string $selectClass,
        string $insertClass,
        string $updateClass,
        string $deleteClass,
        string $identifierClass
    ) {
        $factory = new QueryFactory($engine);

        $select = $factory->select();
        $insert = $factory->insert('users', []);
        $update = $factory->update('users', []);
        $delete = $factory->delete('users');
        $identifier = $factory->identifier();

        $this->assertInstanceOf($selectClass, $select);
        $this->assertInstanceOf($insertClass, $insert);
        $this->assertInstanceOf($updateClass, $update);
        $this->assertInstanceOf($deleteClass, $delete);
        $this->assertInstanceOf($identifierClass, $identifier);

        // Test that default identifier is the same
        $this->assertInstanceOf($identifierClass, Identifier::getDefault());
    }

    public function dataFactory()
    {
        return [
            // engine, select, insert, update, delete, identifier
            'MySQL' => [
                'mysql',
                SelectQuery::class,
                InsertQuery::class,
                UpdateQuery::class,
                DeleteQuery::class,
                MySQL\Identifier::class,
            ],
            'Postgres' => [
                'pgsql',
                SelectQuery::class,
                Postgres\InsertQuery::class,
                Postgres\UpdateQuery::class,
                Postgres\DeleteQuery::class,
                Common\Identifier::class,
            ],
            'SQL Server' => [
                'sqlsrv',
                SelectQuery::class,
                InsertQuery::class,
                UpdateQuery::class,
                DeleteQuery::class,
                SqlServer\Identifier::class,
            ],
            'SQLite' => [
                'sqlite',
                SelectQuery::class,
                InsertQuery::class,
                UpdateQuery::class,
                DeleteQuery::class,
                Common\Identifier::class,
            ],
        ];
    }

    public function testNoDefaultIdentifier()
    {
        $factory = new QueryFactory('pgsql', false);

        // If the default had been set, Common\Identifier would have been returned
        $this->assertSame(Identifier::class, get_class(Identifier::getDefault()));
    }
}
