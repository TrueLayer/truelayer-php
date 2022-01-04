<?php

declare(strict_types=1);

namespace TrueLayer\Services\Payment\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Traits\WithSdk;
use TrueLayer\Validation\BeneficiaryRules;
use TrueLayer\Validation\PaymentRules;

final class PaymentRetrieve
{
    use WithSdk;

    /**
     * @param string $id
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return mixed[]
     */
    public function execute(string $id): array
    {
        return $this->getSdk()->getApiClient()->request()
            ->uri(Endpoints::PAYMENTS . '/' . $id)
            ->responseRules(fn ($data) => $this->getResponseRules($data))
            ->get();
    }

    /**
     * @param mixed[] $data
     *
     * @return mixed[]
     */
    private function getResponseRules(array $data): array
    {
        return \array_merge(
            PaymentRules::rules($data),
            BeneficiaryRules::rules($data),
            [
                'id' => 'required|string',
                'status' => 'required|string',
                'created_at' => 'required|date',
                'user.id' => 'required|string',
                'user.name' => 'required|string',
                'user.phone' => 'required_without:user.email|nullable|string',
                'user.email' => 'required_without:user.phone|nullable|email',
            ]
        );
    }
}
