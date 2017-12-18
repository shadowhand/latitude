<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

/**
 * Class Identifier
 * @package Latitude\QueryBuilder
 */
class Identifier
{
    const IDENTIFIER_REGEX = '/^[a-zA-Z](?:[a-zA-Z0-9_]+)?$/';
    const IDENTIFIER_CAPTURE_REGEX = '/([a-zA-Z](?:[a-zA-Z0-9_]+)?\.[a-zA-Z](?:[a-zA-Z0-9_]+)?)/';

    /**
     * @var Identifier
     */
    protected static $default;

    /**
     * Set the default identifier instance.
     *
     * @param Identifier $default
     * @return void
     */
    public static function setDefault(Identifier $default)
    {
        self::$default = $default;
    }

    /**
     * Get the default identifier instance.
     */
    public static function getDefault(): Identifier
    {
        return self::$default ?: static::make();
    }

    /**
     * Create a new identifier instance.
     */
    public static function make(): Identifier
    {
        return new static();
    }

    /**
     * Escape an unqualified identifier.
     */
    public function escape(string $identifier): string
    {
        if ($identifier === '*') {
            return $identifier;
        }

        $this->guardIdentifier($identifier);
        return $this->surround($identifier);
    }

    /**
     * Escape a (possibly) qualified identifier.
     *
     * @param Expression|string $identifier
     * @return string
     * @throws \TypeError
     */
    public function escapeQualified($identifier): string
    {
        if ($this->isExpression($identifier)) {
            /** @var Expression $identifier */
            return $identifier->sql($this);
        } elseif (!\is_string($identifier)) {
            throw new \TypeError('Expected an Expression or a string.');
        }

        if (\strpos($identifier, '.') === false) {
            return $this->escape($identifier);
        }

        $parts = \explode('.', $identifier);
        return \implode('.', \array_map([$this, 'escape'], $parts));
    }

    /**
     * Escape a identifier alias.
     *
     * @param Expression|string $alias
     * @return string
     * @throws \TypeError
     */
    public function escapeAlias($alias): string
    {
        if ($this->isExpression($alias)) {
            /** @var Expression $alias */
            return $alias->sql($this);
        } elseif (!\is_string($alias)) {
            throw new \TypeError('Expected an Expression or a string.');
        }

        $parts = \preg_split('/ (?:AS )?/i', $alias);
        return \implode(' AS ', \array_map([$this, 'escapeQualified'], $parts));
    }

    /**
     * Escape a list of identifiers.
     */
    public function all(array $identifiers): array
    {
        return \array_map([$this, 'escape'], $identifiers);
    }

    /**
     * Escape a list of (possibly) qualified identifiers.
     */
    public function allQualified(array $identifiers): array
    {
        return \array_map([$this, 'escapeQualified'], $identifiers);
    }

    /**
     * Escape a list of identifier aliases.
     */
    public function allAliases(array $aliases): array
    {
        return \array_map([$this, 'escapeAlias'], $aliases);
    }

    /**
     * Escape all qualified identifiers in an expression.
     */
    public function escapeExpression(string $expression): string
    {
        if (\strpos($expression, '.') === false) {
            return $expression;
        }

        // table.col = other.col -> [table.col, other.col]
        \preg_match_all(self::IDENTIFIER_CAPTURE_REGEX, $expression, $matches);
        // [table.col, ...] -> ["table"."col", ...]
        $matches[1] = \array_map([$this, 'escapeQualified'], $matches[1]);
        // table.col = other.col -> "table"."col" = "other"."col"
        return \str_replace($matches[0], $matches[1], $expression);
    }

    /**
     * Surround the identifier with escape characters.
     */
    protected function surround(string $identifier): string
    {
        return $identifier;
    }

    /**
     * Check if the identifier is an identifier expression.
     *
     * @param mixed $identifier
     * @return bool
     */
    final protected function isExpression($identifier): bool
    {
        return \is_object($identifier) && $identifier instanceof Expression;
    }

    /**
     * Ensure that identifiers match SQL standard.
     *
     * @param string $identifier
     * @return void
     * @throws IdentifierException
     *  If the identifier is not valid.
     */
    final protected function guardIdentifier(string $identifier)
    {
        if (\preg_match('/^[a-zA-Z](?:[a-zA-Z0-9_]+)?$/', $identifier) == false) {
            throw IdentifierException::invalidIdentifier($identifier);
        }
    }
}
