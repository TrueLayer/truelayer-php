<?php

declare(strict_types=1);

namespace TrueLayer\Services\Payment\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Traits\WithSdk;
use TrueLayer\Validation\BeneficiaryRules;
use TrueLayer\Validation\PaymentRules;

final class PaymentCreate
{
    use WithSdk;

    /**
     * @param mixed[] $data
     *
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     *
     * @return mixed[]
     */
    public function execute(PaymentRequestInterface $paymentRequest): array
    {
        return $this->getSdk()->getApiClient()->request()
            ->uri(Endpoints::PAYMENTS)
            ->payload($paymentRequest->toArray())
            ->requestRules(fn ($data) => $this->getRequestRules($data))
            ->responseRules(fn ($data) => $this->getResponseRules($data))
            ->post();
    }

    /**
     * @param mixed[] $data
     *
     * @return mixed[]
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
     * @param mixed[] $data
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
