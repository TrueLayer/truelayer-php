<?php

declare(strict_types=1);

namespace TrueLayer\Exceptions;

use Illuminate\Contracts\Validation\Validator;

class ValidationException extends Exception
{
    /**
     * @var Validator
     */
    private Validator $validator;

    /**
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        parent::__construct('The given data was invalid.');
        $this->validator = $validator;
    }

    /**
     * @return mixed[]
     */
    public function getErrors(): array
    {
        return $this->validator->errors()->messages();
    }
}
