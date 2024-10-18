<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Carbon\Carbon;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Services\Util\Arr;
use TrueLayer\Services\Util\Str;

// TODO: Refactor castData
trait CastsAttributes
{
    /**
     * @var mixed[]
     */
    protected array $casts = [];

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return empty($this->propertyCastMap) ? $this->casts : $this->propertyCastMap;
    }

    /**
     * @param mixed[] $data
     * @param mixed[]|null $casts
     *
     * @return mixed[]
     * @throws InvalidArgumentException
     *
     */
    protected function castData(array $data, ?array $casts = null): array
    {
        $casts = $casts ?: $this->casts();

        foreach ($casts as $path => $abstract) {
            if (Str::endsWith($path, '.*')) {
                $path .= '.';
            }

            $partPaths = \explode('.*.', $path);

            if (\count($partPaths) > 1) {
                $initialPartPath = \array_shift($partPaths);
                $remainingPartPaths = \implode('.*.', $partPaths);
                $partArr = Arr::get($data, $initialPartPath);

                if (\is_array($partArr)) {
                    foreach ($partArr as $key => $item) {
                        $itemKey = 'item';
                        $abstractKey = $remainingPartPaths
                            ? "{$itemKey}.{$remainingPartPaths}"
                            : $itemKey;

                        $casted = $this->castData(
                            [$itemKey => $item],
                            [$abstractKey => $abstract]
                        );

                        $partArr[$key] = $casted['item'];
                    }
                }

                Arr::set($data, $initialPartPath, $partArr);
            } else {
                $partData = Arr::get($data, $path);

                if ($partData !== null) {
                    if ($abstract === \DateTimeInterface::class) {
                        if (\is_string($partData)) {
                            $partData = $this->toDateTime($partData);
                        }
                    } elseif ($abstract === \stdClass::class) {
                        $partData = (object)$partData;
                    } elseif (\is_array($partData) && \is_string($abstract) && (\interface_exists($abstract) || \class_exists($abstract))) {
                        // @phpstan-ignore-next-line
                        $partData = $this->make($abstract, $partData);
                    }
                    Arr::set($data, $path, $partData);
                }
            }
        }

        return $data;
    }

    /**
     * @param string $dateTime
     *
     * @return \DateTimeInterface|null
     */
    protected function toDateTime(string $dateTime): ?\DateTimeInterface
    {
        if (empty($dateTime)) {
            return null;
        }

        try {
            return Carbon::parse($dateTime);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null $data
     *
     * @return T
     * @throws InvalidArgumentException
     *
     */
    abstract protected function make(string $abstract, ?array $data = null);
}
