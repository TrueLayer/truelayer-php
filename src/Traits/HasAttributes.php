<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use TrueLayer\Contracts\ArrayableInterface;

trait HasAttributes
{
    /**
     * @var mixed[]
     */
    protected array $data = [];

    /**
     * @return mixed[]
     * @throws \TrueLayer\Exceptions\ValidationException
     */
    public function toArray(): array
    {
        return array_map(fn ($data) =>
            $data instanceof ArrayableInterface
                ? $data->toArray()
                : $data
        , $this->validate());
    }

    /**
     * @return mixed[]
     * @throws \TrueLayer\Exceptions\ValidationException
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
     * @return $this
     * @throws \TrueLayer\Exceptions\ValidationException
     */
    public function fill(array $data = []): self
    {
        $this->data = $data;
        $this->data = $this->validate();

        return $this;
    }

    /**
     * @param string|int|null $key
     * @param null|mixed $default
     *
     * @return mixed|null
     */
    protected function get($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @param string|null $key
     * @param mixed $value
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
