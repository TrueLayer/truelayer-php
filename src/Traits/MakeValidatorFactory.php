<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;

trait MakeValidatorFactory
{
    /**
     * Build the validation factory
     * Used for validating api requests and responses.
     */
    private function makeValidatorFactory(): ValidatorFactory
    {
        return new \TrueLayer\Validation\ValidatorFactory();
    }
}
