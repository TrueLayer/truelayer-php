<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

final class Type
{
    /**
     * @param mixed[] $data
     * @param string  $key
     *
     * @return mixed[]|null
     */
    public static function getNullableArray(array $data, string $key): ?array
    {
        $value = Arr::get($data, $key);

        return \is_array($value) ? $value : null;
    }

    /**
     * @param mixed[] $data
     * @param string  $key
     *
     * @return string|null
     */
    public static function getNullableString(array $data, string $key): ?string
    {
        $value = Arr::get($data, $key);

        return \is_string($value) ? $value : null;
    }

    /**
     * @param mixed[] $data
     * @param string  $key
     *
     * @return Carbon|null
     */
    public static function getNullableDate(array $data, string $key): ?Carbon
    {
        $value = Arr::get($data, $key);

        if (\is_string($value)) {
            return Carbon::parse($value);
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        return null;
    }
}
