<?php

declare(strict_types=1);

namespace TrueLayer\Services\Payment\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Traits\WithSdk;
use TrueLayer\Validation\BeneficiaryRules;
use TrueLayer\Validation\PaymentRules;

class PaymentCreate
{
    use WithSdk;

    /**
     * @param array $data
     * @return array
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     */
    public function execute(array $data): array
    {
        return $this->getSdk()->getApiClient()->request()
            ->uri(Endpoints::PAYMENTS)
            ->payload($data)
            ->requestRules(fn ($data) => $this->getRequestRules($data))
            ->responseRules(fn ($data) => $this->getResponseRules($data))
            ->post();
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getRequestRules(array $data): array
    {
        $userRules = empty($data['user']['id'])
            ? [
                'user.name' => 'required|string',
                'user.phone' => 'required_without:user.email|nullable|string',
                'user.email' => 'required_without:user.phone|nullable|email',
            ]
            : [
                'user.id' => 'required|string',
            ];

        return \array_merge(
            PaymentRules::rules($data),
            BeneficiaryRules::rules($data),
            $userRules,
        );
    }

    /**
     * @param array $data
     *
     * @return string[]
     */
    private function getResponseRules(array $data): array
    {
        return [
            'id' => 'required|string',
            'user.id' => 'required|string',
            'resource_token' => 'required|string',
        ];
    }
}
