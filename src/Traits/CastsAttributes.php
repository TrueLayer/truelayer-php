<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;

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
        return $this->casts;
    }

    /**
     * @param mixed[]      $data
     * @param mixed[]|null $casts
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return mixed[]
     */
    protected function castData(array $data, array $casts = null): array
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
            } elseif ($partData = Arr::get($data, $path)) {
                if ($abstract === DateTimeInterface::class) {
                    if (\is_string($partData)) {
                        $partData = $this->toDateTime($partData);
                    }
                } elseif (\is_array($partData) && \is_string($abstract) && (\interface_exists($abstract) || \class_exists($abstract))) {
                    // @phpstan-ignore-next-line
                    $partData = $this->make($abstract, $partData);
                }
                Arr::set($data, $path, $partData);
            }
        }

        return $data;
    }

    /**
     * @param string $dateTime
     *
     * @return DateTimeInterface|null
     */
    protected function toDateTime(string $dateTime): ?DateTimeInterface
    {
        try {
            return new \DateTime($dateTime);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @template T
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null    $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return T
     */
    abstract protected function make(string $abstract, array $data = null);
}
