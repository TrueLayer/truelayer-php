<?php

declare(strict_types=1);

namespace TrueLayer\Validation;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\ExternalAccountTypes;

class BeneficiaryRules
{
    /**
     * @param array $data
     * @return array
     */
    public static function rules(array $data): array
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

            if ($schemeType === ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER) {
                $rules['beneficiary.scheme_identifier.sort_code'] = 'required|numeric|digits:6';
                $rules['beneficiary.scheme_identifier.account_number'] = 'required|numeric|digits:8';
                return $rules;
            }
        }

        if ($type === BeneficiaryTypes::MERCHANT_ACCOUNT) {
            $rules['beneficiary.id'] = 'required|string';
        }

        return $rules;
    }
}
