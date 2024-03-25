<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util;

class Str
{
    /**
     * The cache of camel-cased words.
     *
     * @var string[]
     */
    protected static $camelCache = [];

    /**
     * The cache of studly-cased words.
     *
     * @var string[]
     */
    protected static $studlyCache = [];

    /**
     * Return the remainder of a string after the first occurrence of a given value.
     *
     * @param string $subject
     * @param string $search
     *
     * @return string
     */
    public static function after($subject, $search)
    {
        return $search === '' ? $subject : \array_reverse(\explode($search, $subject, 2))[0];
    }

    /**
     * Get the portion of a string before the first occurrence of a given value.
     *
     * @param string $subject
     * @param string $search
     *
     * @return string
     */
    public static function before($subject, $search)
    {
        if ($search === '') {
            return $subject;
        }

        $result = \strstr($subject, (string) $search, true);

        return $result === false ? $subject : $result;
    }

    /**
     * Convert a value to camel case.
     *
     * @param string $value
     *
     * @return string
     */
    public static function camel($value)
    {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }

        return static::$camelCache[$value] = \lcfirst(static::studly($value));
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string                  $haystack
     * @param string|iterable<string> $needles
     *
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        if (!\is_iterable($needles)) {
            $needles = (array) $needles;
        }

        foreach ($needles as $needle) {
            if ((string) $needle !== '' && \str_ends_with($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param string $value
     *
     * @return string
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $words = \explode(' ', \str_replace(['-', '_'], ' ', $value));

        $studlyWords = \array_map(function (string $word) {
            $encoding = 'UTF-8';
            $firstChar = \mb_substr($word, 0, 1, $encoding);
            $then = \mb_substr($word, 1, null, $encoding);

            return \mb_strtoupper($firstChar, $encoding) . $then;
        }, $words);

        return static::$studlyCache[$key] = \implode($studlyWords);
    }
}
