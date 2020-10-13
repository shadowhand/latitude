<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected EngineInterface $engine;

    protected QueryFactory $factory;

    protected function setUp(): void
    {
        $this->engine = $this->getEngine();
        $this->factory = new QueryFactory($this->engine);
    }

    protected function getEngine(): EngineInterface
    {
        return new Engine\BasicEngine();
    }

    public function assertSql(string $sql, StatementInterface $statement): void
    {
        $this->assertSame($sql, $statement->sql($this->engine));
        if (! ($statement instanceof QueryInterface)) {
            return;
        }

        $this->assertSame($sql, $statement->compile()->sql());
    }

    public function assertParams(array $params, StatementInterface $statement): void
    {
        $this->assertSame($params, $statement->params($this->engine));
        if (! ($statement instanceof QueryInterface)) {
            return;
        }

        $this->assertSame($params, $statement->compile()->params());
    }
}
