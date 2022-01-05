<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Exceptions\InvalidArgumentException;

trait HasAttributes
{
    /**
     * @var mixed[]
     */
    protected array $data = [];

    /**
     * @throws \TrueLayer\Exceptions\ValidationException
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return \array_map(fn ($data) => $data instanceof ArrayableInterface
                ? $data->toArray()
                : $data, $this->validate());
    }

    /**
     * @throws \TrueLayer\Exceptions\ValidationException
     *
     * @return mixed[]
     */
    public function validate(): array
    {
        $validator = $this->validator();

        try {
            return $validator->validate();
        } catch (ValidationException $e) {
            throw new \TrueLayer\Exceptions\ValidationException($validator);
        }
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return !$this->validator()->fails();
    }

    /**
     * @return mixed[]
     */
    public function errors(): array
    {
        return $this->validator()->errors()->toArray();
    }

    /**
     * @param mixed[] $data
     *
     * @throws \TrueLayer\Exceptions\ValidationException
     *
     * @return $this
     */
    public function fill(array $data = []): self
    {
        $this->data = $data;
        $this->data = $this->validate();

        return $this;
    }

    /**
     * @param string|int|null $key
     * @param mixed|null      $default
     *
     * @return mixed|null
     */
    protected function get($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @param string|int|null $key
     * @param string|null     $default
     *
     * @return string|null
     */
    protected function getNullableString($key, string $default = null): ?string
    {
        $val = $this->get($key, $default);

        return \is_string($val) ? $val : null;
    }

    /**
     * @param string|int|null $key
     * @param string|null     $default
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    protected function getString($key, string $default = null): string
    {
        $val = $this->get($key, $default);

        if (!\is_string($val)) {
            throw new InvalidArgumentException("{$key} cannot be NULL");
        }

        return $val;
    }

    /**
     * @param string|int|null $key
     * @param int|null        $default
     *
     * @return int|null
     */
    protected function getNullableInt($key, int $default = null): ?int
    {
        $val = $this->get($key, $default);

        return \is_int($val) ? $val : null;
    }

    /**
     * @param string|int|null $key
     * @param int|null        $default
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    protected function getInt($key, int $default = null): int
    {
        $val = $this->get($key, $default);

        if (!\is_int($val)) {
            throw new InvalidArgumentException("{$key} cannot be NULL");
        }

        return $val;
    }

    /**
     * @param string|int|null $key
     * @param bool|null       $default
     *
     * @return bool
     */
    protected function getBool($key, bool $default = null): bool
    {
        $val = $this->get($key, $default);

        return \is_bool($val) ? $val : false;
    }

    /**
     * @param string|null $key
     * @param mixed       $value
     *
     * @return $this
     */
    protected function set($key, $value): self
    {
        Arr::set($this->data, $key, $value);

        return $this;
    }

    /**
     * @return Validator
     */
    private function validator(): Validator
    {
        return $this->getSdk()->getValidatorFactory()->make(
            $this->data,
            $this->rules()
        );
    }
}
