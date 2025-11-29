<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder\Query;

use mysqli;
use mysqli_stmt;
use PDO;
use PDOStatement;
use SQLite3;
use SQLite3Stmt;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\ExpressionInterface;
use Latitude\QueryBuilder\Query;
use Latitude\QueryBuilder\QueryInterface;

abstract class AbstractQuery implements QueryInterface
{
    protected EngineInterface $engine;

    public function __construct(
        EngineInterface $engine
    ) {
        $this->engine = $engine;
    }

    abstract public function asExpression(): ExpressionInterface;

    abstract protected function startExpression(): ExpressionInterface;

    public function compile(): Query
    {
        $query = $this->asExpression();

        return new Query(
            $query->sql($this->engine),
            $query->params($this->engine)
        );
    }

    public function sql(EngineInterface $engine): string
    {
        return $this->asExpression()->sql($engine);
    }

    public function params(EngineInterface $engine): array
    {
        return $this->asExpression()->params($engine);
    }

    public function stmtFromPDO(PDO $pdo, EngineInterface $engine): PDOStatement
    {
        $stmt = $pdo->prepare($this->sql($engine));

        foreach ($this->params($engine) as $i => $value) {
            $stmt->bindValue(
                $i + 1,
                $value,
                match (get_debug_type($value)) {
                    'null' => PDO::PARAM_NULL,
                    'bool' => PDO::PARAM_BOOL,
                    'int' => PDO::PARAM_INT,
                    'float' => PDO::PARAM_STR,
                    'string' => PDO::PARAM_STR,
                    default => PDO::PARAM_STR
                }
            );
        }

        return $stmt;
    }

    public function stmtFromMysqli(mysqli $mysqli, EngineInterface $engine): mysqli_stmt
    {
        $stmt = $mysqli->prepare($this->sql($engine));

        $types = [];
        $values = [];

        foreach ($this->params($engine) as $i => $value) {
            $types[] = match (get_debug_type($value)) {
                'null' => 's',
                'int' => 'i',
                'float' => 'd',
                'string' => 's',
                default => 's'
            };

            $values[] = $value;
        }

        $stmt->bind_param(
            implode(
                '',
                $types
            ),
            ...$values
        );

        return $stmt;
    }

    public function stmtFromSQLite3(SQLite3 $sqlite3, EngineInterface $engine): SQLite3Stmt
    {
        $stmt = $sqlite3->prepare($this->sql($engine));

        foreach ($this->params($engine) as $i => $value) {
            $stmt->bindValue(
                $i + 1,
                $value,
                match (get_debug_type($value)) {
                    'null' => SQLITE3_NULL,
                    'int' => SQLITE3_INTEGER,
                    'float' => SQLITE3_FLOAT,
                    'string' => SQLITE3_TEXT,
                    default => SQLITE3_TEXT
                }
            );
        }

        return $stmt;
    }
}
