<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

function isStatement($value): bool
{
    return $value instanceof StatementInterface;
}

function alias($field, string $alias): ExpressionInterface
{
    return express('%s AS %s', identify($field), identify($alias));
}

function func(string $function, ...$replacements): ExpressionInterface
{
    return express("$function(%s)", listing(identifyAll($replacements)));
}

function literal($value): StatementInterface
{
    return isStatement($value) ? $value : new Partial\Literal($value);
}

function on(string $left, string $right): CriteriaInterface
{
    return criteria('%s = %s', identify($left), identify($right));
}

function order($column, string $direction = null): StatementInterface
{
    if (empty($direction)) {
        return identify($column);
    }
    return express(sprintf("%%s %s", strtoupper($direction)), identify($column));
}

function criteria(string $pattern, ...$replacements): CriteriaInterface
{
    return new Partial\Criteria(express($pattern, ...$replacements));
}

function group(CriteriaInterface $criteria): CriteriaInterface
{
    return criteria('(%s)', $criteria);
}

function field($name): Builder\CriteriaBuilder
{
    return new Builder\CriteriaBuilder(identify($name));
}

function search($name): Builder\LikeBuilder
{
    return new Builder\LikeBuilder(identify($name));
}

function express(string $pattern, ...$replacements): ExpressionInterface
{
    return new Partial\Expression($pattern, ...paramAll($replacements));
}

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

function param($value): StatementInterface
{
    return isStatement($value) ? $value : new Partial\Parameter($value);
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
