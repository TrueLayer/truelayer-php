<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util;

class Arr
{
    /**
     * Determine whether the given value is array accessible.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function accessible($value)
    {
        return \is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param \ArrayAccess<mixed, mixed>|mixed[] $array
     * @param string|int|float $key
     *
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        if (\is_float($key)) {
            $key = (string)$key;
        }

        return \array_key_exists($key, $array);
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param iterable $array
     * @param int $depth
     *
     * @return array
     */
    public static function flatten($array, $depth = INF) // @phpstan-ignore-line
    {
        $result = [];

        foreach ($array as $item) {
            if (!\is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1
                    ? \array_values($item)
                    : static::flatten($item, $depth - 1);

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param \ArrayAccess<mixed, mixed>|mixed[] $array
     * @param string|int|null $key
     * @param mixed $default
     *
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (!static::accessible($array)) {
            return $default;
        }

        if (\is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (!\str_contains((string)$key, '.')) {
            return $array[$key] ?? $default;
        }

        foreach (\explode('.', (string)$key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param mixed[] $array
     *
     * @return bool
     */
    public static function isAssoc($array)
    {
        $keys = \array_keys($array);

        return \array_keys($keys) !== $keys;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param mixed[] $array
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed[]|mixed
     */
    public static function set(&$array, $key, $value)
    {
        if (\is_null($key)) {
            return $array = $value;
        }

        $keys = \explode('.', (string)$key);

        foreach ($keys as $i => $key) {
            if (\count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !\is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[\array_shift($keys)] = $value;

        return $array;
    }
}
