<?php

declare(strict_types=1);

namespace TrueLayer\Validation;

use Illuminate\Contracts\Validation\Validator as ValidatorInterface;
use Illuminate\Support\MessageBag;

final class Validator implements ValidatorInterface
{
    public function getMessageBag(): MessageBag
    {
        return new MessageBag();
    }

    /**
     * @return mixed[]
     */
    public function validate(): array
    {
        return [];
    }

    /**
     * @return mixed[]
     */
    public function validated(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function fails(): bool
    {
        return false;
    }

    /**
     * @return mixed[]
     */
    public function failed(): array
    {
        return [];
    }

    /**
     * @param mixed[]|string $attribute
     * @param mixed[]|string $rules
     * @param callable       $callback
     *
     * @return $this
     */
    public function sometimes($attribute, $rules, callable $callback): self
    {
        return $this;
    }

    /**
     * @param callable|string $callback
     *
     * @return $this
     */
    public function after($callback): self
    {
        return $this;
    }

    /**
     * @return MessageBag
     */
    public function errors(): MessageBag
    {
        return new MessageBag();
    }
}
