<?php

declare(strict_types=1);

namespace TrueLayer\Validation;

use Illuminate\Contracts\Validation\Rule;
use TrueLayer\Contracts\HasValidationInterface;
use TrueLayer\Exceptions\ValidationException;

final class ValidType implements Rule
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
     * @param mixed $value
     * @return bool
     * @throws ValidationException
     */
    public function passes($attribute, $value): bool
    {
        if (!($value instanceof $this->class)) {
            return false;
        }

        if ($value instanceof HasValidationInterface) {
            $value->validate();
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The provided :attribute value is not valid.';
    }

    /**
     * @param class-string $class
     *
     * @return ValidType
     */
    public static function of(string $class): ValidType
    {
        return new self($class);
    }
}
