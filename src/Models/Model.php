<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\HasAttributesInterface;
use TrueLayer\Traits\WithSdk;

abstract class Model implements ArrayableInterface, HasAttributesInterface
{
    use WithSdk;

    /**
     * @var string[]
     */
    protected array $arrayFields = [];

    /**
     * @var mixed[]
     */
    protected array $rules = [];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return $this->rules;
    }

    /**
     * @throws \TrueLayer\Exceptions\ValidationException
     *
     * @return self
     */
    public function validate(): self
    {
        $validator = $this->validator();

        try {
            $validator->validate();
            return $this;
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
     * @return Validator
     */
    protected function validator(): Validator
    {
        return $this->getSdk()->getValidatorFactory()->make(
            $this->all(),
            $this->rules()
        );
    }

    /**
     * Convert to array
     * @return mixed[]
     */
    public function toArray(): array
    {
        return array_map(
            fn ($val) => $val instanceof ArrayableInterface ? $val->toArray() : $val,
            $this->all()
        );
    }

    /**
     * @param mixed[] $data
     * @return $this
     * @throws \TrueLayer\Exceptions\ValidationException
     */
    public function fill(array $data): self
    {
        $this->setValues($data);
        $this->validate();
        return $this;
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    protected function setValues(array $data): self
    {
        $data = Arr::dot($data);

        foreach ($data as $key => $value) {
            $key = $this->arrayFields[$key] ?? $key;
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Model
     */
    protected function set(string $key, $value): self
    {
        $property = Str::camel($key);

        if ($value !== null) {
            if (method_exists($this, $property)) {
                $this->$property($value);
            } elseif (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }

        return $this;
    }

    /**
     * @return mixed[]
     */
    protected function all(): array
    {
        $array = [];

        foreach ($this->arrayFields as $dotNotation => $propertyKey) {
            $dotNotation = is_int($dotNotation) ? $propertyKey : $dotNotation;
            $propertyKey = Str::camel($propertyKey);
            $method = Str::camel("get_" . $propertyKey);

            $value = method_exists($this, $method)
                ? $this->$method() : (
                property_exists($this, $propertyKey)
                    ? $this->$propertyKey
                    : null
                );

            Arr::set($array, $dotNotation, $value);
        }

        return $array;
    }
}
