<?php

declare(strict_types=1);

namespace TrueLayer\Api\Handlers;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Models\PaymentInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiRequestValidationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\ApiResponseValidationException;
use TrueLayer\Models\Payment;
use TrueLayer\Validation\BeneficiaryRules;
use TrueLayer\Validation\PaymentRules;

class PaymentRetrieve
{
    /**
     * @param ApiClientInterface $api
     * @param string             $id
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiRequestValidationException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiResponseValidationException
     *
     * @return PaymentInterface
     */
    public function execute(ApiClientInterface $api, string $id): PaymentInterface
    {
        $data = $api->request()
            ->uri(Endpoints::PAYMENTS . '/' . $id)
            ->responseRules(fn ($data) => $this->getResponseRules($data))
            ->get();

        return Payment::fromArray($data);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getResponseRules(array $data): array
    {
        return \array_merge(
            PaymentRules::rules($data),
            BeneficiaryRules::rules($data),
            [
                'id' => 'required|string',
                'user.id' => 'required|string',
                'user.name' => 'required|string',
                'user.phone' => 'required_without:user.email|nullable|string',
                'user.email' => 'required_without:user.phone|nullable|email',
            ]
        );
    }
}
