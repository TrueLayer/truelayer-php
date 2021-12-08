<?php

declare(strict_types=1);

namespace TrueLayer\Exceptions;

class ValidationException extends \Illuminate\Validation\ValidationException
{
    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors();
    }
}
