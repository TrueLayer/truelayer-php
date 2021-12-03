<?php

declare(strict_types=1);

namespace TrueLayer\Api\Handlers;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Constants\UserTypes;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Models\PaymentInterface;
use TrueLayer\Contracts\Models\PaymentCreatedInterface;
use TrueLayer\Models\PaymentCreated;
use TrueLayer\Validation\BeneficiaryRules;
use TrueLayer\Validation\PaymentRules;

class PaymentCreate
{
    /**
     * @param ApiClientInterface $client
     * @param PaymentInterface $model
     * @return PaymentCreatedInterface
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     */
    public function execute(ApiClientInterface $api, PaymentInterface $model): PaymentCreatedInterface
    {
        $data = $api->request()
            ->uri(Endpoints::PAYMENTS)
            ->payload($this->getPayload($model))
            ->requestRules(fn ($data) => $this->getRequestRules($data))
            ->responseRules(fn ($data) => $this->getResponseRules($data))
            ->post();

        return PaymentCreated::fromArray($data);
    }

    /**
     * @param PaymentInterface $model
     * @return array
     */
    private function getPayload(PaymentInterface $model): array
    {
        $payload = $model->toArray();

        if ($user = $model->getUser()) {
            $payload['user']['type'] = $user->getId() ? UserTypes::EXISTING : UserTypes::NEW;
            $payload['user'] = array_filter($payload['user']);
        }

        return $payload;
    }

    /**
     * @param array $data
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

        return array_merge(
            PaymentRules::rules($data),
            BeneficiaryRules::rules($data),
            $userRules,
        );
    }

    /**
     * @param array $data
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
