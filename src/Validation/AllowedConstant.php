<?php

declare(strict_types=1);

namespace TrueLayer\Validation;

use Illuminate\Contracts\Validation\Rule;

final class AllowedConstant implements Rule
{
    /**
     * @var class-string
     */
    private string $class;

    /**
     * @param class-string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     *
     * @throws \ReflectionException
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $constants = (new \ReflectionClass($this->class))->getConstants();

        return \in_array($value, \array_values($constants));
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The provided :attribute value is not allowed.';
    }

    /**
     * @param class-string $class
     *
     * @return AllowedConstant
     */
    public static function in(string $class): AllowedConstant
    {
        return new static($class);
    }
}
