<?php

declare(strict_types=1);

namespace TrueLayer\Validation;

use Illuminate\Contracts\Validation\Factory;

final class ValidatorFactory implements Factory
{
    /**
     * @param mixed[] $data
     * @param mixed[] $rules
     * @param mixed[] $messages
     * @param mixed[] $customAttributes
     *
     * @return Validator
     */
    public function make(array $data, array $rules, array $messages = [], array $customAttributes = []): Validator
    {
        return new Validator();
    }

    /**
     * @param string          $rule
     * @param \Closure|string $extension
     * @param null            $message
     */
    public function extend($rule, $extension, $message = null)
    {
    }

    /**
     * @param string          $rule
     * @param \Closure|string $extension
     * @param null            $message
     */
    public function extendImplicit($rule, $extension, $message = null)
    {
    }

    /**
     * @param string          $rule
     * @param \Closure|string $replacer
     */
    public function replacer($rule, $replacer)
    {
    }
}
