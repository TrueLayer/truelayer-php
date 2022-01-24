<?php

declare(strict_types=1);

namespace TrueLayer\Validation;

use Illuminate\Contracts\Validation\Rule;
use TrueLayer\Exceptions\ValidationException;

final class ValidType implements Rule
{
    /**
     * @var class-string[]
     */
    private array $classes;

    /**
     * @param class-string[] $classes
     */
    public function __construct(array $classes)
    {
        $this->classes = $classes;
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     *
     * @throws ValidationException
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (!$this->instanceOf($value)) {
            return false;
        }

        if (\method_exists($value, 'validate')) {
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
     * @param mixed $value
     *
     * @return bool
     */
    private function instanceOf($value): bool
    {
        foreach ($this->classes as $class) {
            if ($value instanceof $class) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param class-string ...$class
     *
     * @return ValidType
     */
    public static function of(string ...$class): ValidType
    {
        return new self($class);
    }
}
