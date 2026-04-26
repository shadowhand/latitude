<?php

declare(strict_types=1);

namespace Latitude\QueryBuilder;

use function count;
use function preg_replace_callback;
use function ini_get;
use function ini_set;
use function is_bool;
use function sprintf;

final class QueryWithNamedParams
{
    public const DEFAULT_PARAM_NAME_TEMPLATE = ':param_%d';

    /**
     * Matches:
     * ? outside of ' " ` [] or sql comments (with capture group)
     * Comments --, #, and /* (without capture group)
     */
    public const REGEX_PATTERN_QUESTION_MARKS = '/(?:\'(?:\\\\.|[^\\\\\'])*\'|\"(?:\\\\.|[^\\\\\"])*\"|\`(?:\\\\.|[^\\\\\`])*\`|\[(?:\\\\.|[^\[\]])*?\]|(\?)(?=(?:[^\'\"\`\[\]]|\'(?:\\\\.|[^\\\\\'])*\'|\"(?:\\\\.|[^\\\\\"])*\"|\`(?:\\\\.|[^\\\\\`])*\`|\[(?:\\\\.|[^\[\]])*?\])*$)|(?:\-\-[^\r\n]*|\/\*[\s\S]*?\*\/|\#.*))/m';
    public const INI_PCRE_JIT = 'pcre.jit';

    private string $sql;
    private array $params;

    public function __construct(string $sql, array $params, string $template = self::DEFAULT_PARAM_NAME_TEMPLATE)
    {
        if (count($params) == 0) {
            $this->sql = $sql;
            $this->params = $params;

            return;
        }

        $index = 0;
        $namedParams = [];

        $sqlWithNamedParams = $this->pregReplaceCallback(
            static::REGEX_PATTERN_QUESTION_MARKS,
            function (array $match) use ($template, $params, &$index, &$namedParams): string {
                if (!$this->isQuestionMarkMatch($match)) {
                    return $match[0];
                }

                $key = sprintf($template, $index + 1);
                $value = $params[$index];

                $namedParams[$key] = $value;

                $index++;

                return $key;
            },
            $sql
        );

        $this->sql = $sqlWithNamedParams;
        $this->params = $namedParams;
    }

    public function sql(): string
    {
        return $this->sql;
    }

    public function params(): array
    {
        return $this->params;
    }

    private function pregReplaceCallback(string $pattern, callable $callback, string $subject): string
    {
        /**
         * To prevent possible out-of-memory issues, PCRE just in time compilation needs to be temporarily disabled
         * This can only happen with large recursive capture groups when nested quotes or very large IN lists are present
         */
        $ini = ini_get(static::INI_PCRE_JIT);

        ini_set(static::INI_PCRE_JIT, '0');

        $result = (string) preg_replace_callback($pattern, $callback, $subject);

        if (!is_bool($ini)) {
            ini_set(static::INI_PCRE_JIT, $ini);
        }

        return $result;
    }

    private function isQuestionMarkMatch(array $match): bool
    {
        return count($match) > 1;
    }
}
