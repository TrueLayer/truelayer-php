<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use TrueLayer\Attributes\Field;
use TrueLayer\Constants\DateTime;
use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Services\Util\Arr;
use TrueLayer\Services\Util\Str;

trait ArrayableAttributes
{
    /**
     * Map the array fields to instance properties
     * The key is the array field path using dot notation (ex. 'my.nested.field')
     * The value is either the name of the instance property or setter method in either camelCase or snake_case
     * (ex. 'myField' or 'my_field').
     *
     * @var string[]
     */
    protected array $arrayFields = [];

    /**
     * Convert to array.
     * Nested values will also be converted to array if they implement ArrayableInterface.
     * The final array shape will be a nested array of the shape provided by $arrayFields.
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return \array_map(
            [$this, 'convertToValueForArray'],
            $this->all()
        );
    }

    /**
     * @return string[]
     */
    protected function arrayFields(): array
    {
        return $this->arrayFields;
    }

    /**
     * Set instance property values from an array
     * The array can be nested and the nested values will be set on the instance properties
     * according to the $arrayFields property, using dot notation for nested paths.
     *
     * @param mixed[] $data
     *
     * @return $this
     */
    protected function setValues(array $data): self
    {
        $maybeUsingDotNotation = [];

        foreach ($data as $key => $value) {
            if (!$this->set($key, $value)) {
                $maybeUsingDotNotation[$key] = $value;
            }
        }

        foreach ($this->flatten($maybeUsingDotNotation) as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Set an instance property.
     *
     * @param string $key the instance property name or the setter name
     * @param mixed $value The instance property value
     *
     * @return bool
     */
    protected function set(string $key, $value): bool
    {
        $property = Str::camel($this->arrayFields()[$key] ?? $key);

        if ($value === null) {
            return false;
        }

        if (\method_exists($this, $property)) {
            $this->{$property}($value);

            return true;
        }

        if (\property_exists($this, $property)) {
            $this->{$property} = $value;

            return true;
        }

        return false;
    }

    /**
     * Get an array of all the properties
     * The array shape will be a nested array of the shape provided by $arrayFields.
     *
     * @return mixed[]
     */
    protected function all(): array
    {
        $array = [];

        foreach ($this->getArrayFieldsFromAttributes() as $dotNotation => $propertyKey) {
            $dotNotation = \is_int($dotNotation) ? $propertyKey : $dotNotation;
            $propertyKey = Str::camel($propertyKey);
            $method = Str::camel('get_' . $propertyKey);

            $value = \method_exists($this, $method)
                ? $this->{$method}() : (
                \property_exists($this, $propertyKey) && isset($this->{$propertyKey})
                    ? $this->{$propertyKey}
                    : null
                );

            Arr::set($array, $dotNotation, $value);
        }

        return $array;
    }

    /**
     * Flatten associative arrays using dot notation.
     * Nested sequential arrays will not be nested.
     *
     * @param mixed[] $array
     * @param string $prepend
     *
     * @return mixed[]
     */
    private function flatten(array $array, string $prepend = ''): array
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (\is_array($value) && !empty($value) && Arr::isAssoc($value)) {
                $results = \array_merge($results, $this->flatten($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private function convertToValueForArray($value)
    {
        if (\is_array($value)) {
            return \array_map([$this, 'convertToValueForArray'], $value);
        }

        if ($value instanceof ArrayableInterface) {
            return $value->toArray();
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DateTime::FORMAT);
        }

        return $value;
    }

    private function getArrayFieldsFromAttributes()
    {
        $reflectionClass = new \ReflectionClass($this);

        $arrayFields = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(Field::class);

            if (!empty($attributes)) {
                $propertyName = $property->getName();
                $dotNotationFieldName = $attributes[0]->newInstance()->name ?? Str::snake($propertyName);
                $arrayFields[$dotNotationFieldName] = $propertyName;
            }
        }

        return $arrayFields;
    }
}
