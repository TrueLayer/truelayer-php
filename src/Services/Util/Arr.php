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
     * Return the first element in an array passing a given truth test.
     *
     * @param iterable $array
     * @param callable|null $callback
     * @param mixed $default
     * @return mixed
     */
    public static function first($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return $default;
            }

            foreach ($array as $item) {
                return $item;
            }

            return $default;
        }

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return $default;
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
     * Pluck an array of values from an array.
     *
     * @param iterable $array
     * @param string|array|int|null $value
     * @param string|array|null $key
     * @return array
     */
    public static function pluck($array, $value, $key = null)
    {
        $results = [];

        [$value, $key] = static::explodePluckParameters($value, $key);

        foreach ($array as $item) {
            $itemValue = data_get($item, $value);

            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = data_get($item, $key);

                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string)$itemKey;
                }

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
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
