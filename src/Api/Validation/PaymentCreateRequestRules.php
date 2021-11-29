<?php

declare(strict_types=1);

namespace TrueLayer\Api\Validation;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\ExternalAccountTypes;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Validation\AllowedConstant;

class PaymentCreateRequestRules
{
    /**
     * @param array $data
     * @return array
     */
    public function __invoke(array $data): array
    {
        $rules = [
            'amount_in_minor' => 'required|int|min:1',
            'currency' => [ 'required', 'string', AllowedConstant::in(Currencies::class) ],
            'payment_method.type' => [ 'required', 'string', AllowedConstant::in(PaymentMethods::class)],
            'payment_method.statement_reference' => 'required|string',
            'user' => 'required|array',
            'beneficiary' => 'required|array',
        ];

        return array_merge(
            $rules,
            $this->requestBeneficiaryRules($data),
            $this->requestUserRules($data)
        );
    }

    /**
     * @param array $data
     * @return string[]
     */
    private function requestUserRules(array $data): array
    {
        return empty($data['user']['id'])
            ? [
                'user.name' => 'required|string',
                'user.phone' => 'required_without:user.email|nullable|string',
                'user.email' => 'required_without:user.phone|nullable|email',
            ]
            : [
                'user.id' => 'required|string',
            ];
    }

    /**
     * @param array $data
     * @return array
     */
    private function requestBeneficiaryRules(array $data): array
    {
        $rules = [
            'beneficiary.type' => [ 'required', 'string', AllowedConstant::in(BeneficiaryTypes::class) ],
            'beneficiary.name' => 'required|string',
        ];

        if (empty($data['beneficiary'])) {
            return $rules;
        }

        $beneficiary = $data['beneficiary'];
        $type = $beneficiary['type'];

        if ($type === BeneficiaryTypes::EXTERNAL_ACCOUNT) {

            $schemeType = $beneficiary['scheme_identifier']['type'] ?? null;
            $rules['beneficiary.reference'] = 'required|string';
            $rules['beneficiary.scheme_identifier'] = 'required|array';

            if ($schemeType === ExternalAccountTypes::IBAN) {
                $rules['beneficiary.scheme_identifier.type'] = 'required|string';
                $rules['beneficiary.scheme_identifier.iban'] = 'required|string';
                return $rules;
            }

            if ($schemeType === ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER){
                $rules['beneficiary.scheme_identifier.sort_code'] = 'required|string';
                $rules['beneficiary.scheme_identifier.account_number'] = 'required|numeric|max:99999999';
                return $rules;
            }
        }

        if ($type === BeneficiaryTypes::MERCHANT_ACCOUNT) {
            $rules['beneficiary.id'] = 'required|string';
        }

        return $rules;
    }
}
