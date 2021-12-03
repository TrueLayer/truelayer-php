<?php

declare(strict_types=1);

namespace TrueLayer\Validation;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Constants\PaymentMethods;

class PaymentRules
{
    /**
     * @param array $data
     * @return array
     */
    public static function rules(array $data): array
    {
        return [
            'amount_in_minor' => 'required|int|min:1',
            'currency' => [ 'required', 'string', AllowedConstant::in(Currencies::class) ],
            'payment_method.type' => [ 'required', 'string', AllowedConstant::in(PaymentMethods::class)],
            'payment_method.statement_reference' => 'required|string',
            'user' => 'required|array',
            'beneficiary' => 'required|array',
        ];
    }
}
