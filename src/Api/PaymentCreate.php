<?php

declare(strict_types=1);

namespace TrueLayer\Api;

use TrueLayer\Api\Validation\PaymentCreateRequestRules;
use TrueLayer\Api\Validation\PaymentCreateResponseRules;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Constants\UserTypes;
use TrueLayer\Contracts\Api\ApiClientInterface;
use TrueLayer\Contracts\Api\PaymentCreateInterface;
use TrueLayer\Contracts\Models\PaymentInterface;
use TrueLayer\Contracts\Models\PaymentCreatedInterface;
use TrueLayer\Models\PaymentCreated;

class PaymentCreate implements PaymentCreateInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $apiClient;

    /**
     * @param ApiClientInterface $apiClient
     */
    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @param PaymentInterface $model
     * @return PaymentCreatedInterface
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     * @throws \TrueLayer\Exceptions\AuthTokenRetrievalFailure
     */
    public function send(PaymentInterface $model): PaymentCreatedInterface
    {
        $data = $this->apiClient->post(
            Endpoints::PAYMENTS,
            $this->getPayload($model),
            new PaymentCreateRequestRules(),
            new PaymentCreateResponseRules()
        );

        return new PaymentCreated($data);
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
}
