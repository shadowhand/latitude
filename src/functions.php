<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder;

use Latitude\QueryBuilder\Partial\Parameter;

use function array_key_exists;
use function array_map;
use function explode;
use function ini_get;
use function ini_set;
use function is_bool;
use function is_null;
use function sprintf;
use function strpos;
use function strtoupper;

/**
 * @param mixed $value
 */
function isStatement($value): bool
{
    return $value instanceof StatementInterface;
}

/**
 * @param mixed $field
 */
function alias($field, string $alias): ExpressionInterface
{
    return express('%s AS %s', identify($field), identify($alias));
}

/**
 * @param mixed ...$replacements
 */
function func(string $function, ...$replacements): ExpressionInterface
{
    $function = sprintf('%s(%%s)', $function);

    return express($function, listing(identifyAll($replacements)));
}

/**
 * @param mixed $value
 */
function literal($value): StatementInterface
{
    return isStatement($value) ? $value : new Partial\Literal($value);
}

function on(string $left, string $right): CriteriaInterface
{
    return criteria('%s = %s', identify($left), identify($right));
}

/**
 * @param mixed $column
 */
function order($column, ?string $direction = null): StatementInterface
{
    if (! $direction) {
        return identify($column);
    }

    return express(sprintf('%%s %s', strtoupper($direction)), identify($column));
}

/**
 * @param mixed ...$replacements
 */
function criteria(string $pattern, ...$replacements): CriteriaInterface
{
    return new Partial\Criteria(express($pattern, ...$replacements));
}

function group(CriteriaInterface $criteria): CriteriaInterface
{
    return criteria('(%s)', $criteria);
}

/**
 * @param mixed $name
 */
function field($name): Builder\CriteriaBuilder
{
    return new Builder\CriteriaBuilder(identify($name));
}

/**
 * @param mixed $name
 */
function search($name): Builder\LikeBuilder
{
    return new Builder\LikeBuilder(identify($name));
}

/**
 * @param mixed ...$replacements
 */
function express(string $pattern, ...$replacements): ExpressionInterface
{
    return new Partial\Expression($pattern, ...paramAll($replacements));
}

/**
 * @param mixed $name
 */
function identify($name): StatementInterface
{
    if (isStatement($name)) {
        return $name;
    }

    if (strpos($name, '.') !== false) {
        return new Partial\IdentifierQualified(...identifyAll(explode('.', $name)));
    }

    if ($name === '*') {
        return new Partial\Literal($name);
    }

    return new Partial\Identifier($name);
}

/**
 * @return StatementInterface[]
 */
function identifyAll(array $names): array
{
    return array_map('Latitude\QueryBuilder\identify', $names);
}

/**
 * @param mixed $value
 */
function param($value): StatementInterface
{
    if (isStatement($value)) {
        return $value;
    }

    return Parameter::create($value);
}

/**
 * @return StatementInterface[]
 */
function paramAll(array $values): array
{
    return array_map('Latitude\QueryBuilder\param', $values);
}

function listing(array $values, string $separator = ', '): Partial\Listing
{
    return new Partial\Listing($separator, ...paramAll($values));
}

function compileRawSql(string $sql, array $params): string
{
    if (!$params) {
        return $sql;
    }

    $ini = ini_get('pcre.jit');

    ini_set('pcre.jit', '0');

    try {
        $index = 0;

        return preg_replace_callback(
            '/(?:\'(?:\\\\.|[^\\\\\'])*\'|\"(?:\\\\.|[^\\\\\"])*\"|\`(?:\\\\.|[^\\\\\`])*\`|\[(?:\\\\.|[^\[\]])*?\]|(\?)(?=(?:[^\'\"\`\[\]]|\'(?:\\\\.|[^\\\\\'])*\'|\"(?:\\\\.|[^\\\\"])*\"|\`(?:\\\\.|[^\\\\\`])*\`|\[(?:\\\\.|[^\[\]])*?\])*$)|(?:--[^\r\n]*|\/\*[\s\S]*?\*\/|\#.*))/m',
            function (array $match) use ($params, &$index): mixed {
                if (count($match) == 1) {
                    return $match[0];
                }

                $key = $match[1];

                if (!array_key_exists($key, $this->params)) {
                    throw new \Exception('sql placeholder and param count do not match');
                }

                $value = $params[$index];

                $index++;

                return !is_null($value) ? $value : 'NULL';
            },
            $sql
        );
    } catch (\Throwable $exception) {
        throw $exception;
    } finally {
        if (!is_bool($ini)) {
            ini_set('pcre.jit', $ini);
        }
    }
}
