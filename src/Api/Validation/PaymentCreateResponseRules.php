<?php

declare(strict_types=1);

namespace TrueLayer\Api\Validation;


class PaymentCreateResponseRules
{
    public function __invoke()
    {
        return [
            'id' => 'required|string',
            'user.id' => 'required|string',
            'resource_token' => 'required|string',
        ];
    }
}
