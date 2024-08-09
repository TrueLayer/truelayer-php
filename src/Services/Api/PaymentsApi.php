<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\PaymentsApiInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;

final class PaymentsApi extends Api implements PaymentsApiInterface
{
    /**
     * @param mixed[]                      $paymentRequest
     * @param RequestOptionsInterface|null $requestOptions
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function create(array $paymentRequest, ?RequestOptionsInterface $requestOptions = null): array
    {
        return (array) $this->request()
            ->requestOptions($requestOptions)
            ->uri(Endpoints::PAYMENTS_CREATE)
            ->payload($paymentRequest)
            ->post();
    }

    /**
     * @param string $id
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed[]
     */
    public function retrieve(string $id): array
    {
        $uri = \str_replace('{id}', $id, Endpoints::PAYMENTS_RETRIEVE);

        return (array) $this->request()->uri($uri)->get();
    }

    /**
     * @param string  $id
     * @param mixed[] $authorizationFlowRequest
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed[]
     */
    public function startAuthorizationFlow(string $id, array $authorizationFlowRequest): array
    {
        $uri = \str_replace('{id}', $id, Endpoints::PAYMENTS_START_AUTH_FLOW);

        return (array) $this->request()
            ->uri($uri)
            ->payload($authorizationFlowRequest)
            ->post();
    }

    /**
     * @param string $id
     * @param string $providerId
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed[]
     */
    public function submitProvider(string $id, string $providerId): array
    {
        $uri = \str_replace('{id}', $id, Endpoints::PAYMENTS_SUBMIT_PROVIDER);

        return (array) $this->request()
            ->uri($uri)
            ->payload(['provider_id' => $providerId])
            ->post();
    }

    /**
     * @param string                       $paymentId
     * @param mixed[]                      $refundRequest
     * @param RequestOptionsInterface|null $requestOptions
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function createRefund(string $paymentId, array $refundRequest, ?RequestOptionsInterface $requestOptions = null): array
    {
        $uri = \str_replace('{id}', $paymentId, Endpoints::PAYMENTS_REFUNDS_CREATE);

        return (array) $this->request()
            ->requestOptions($requestOptions)
            ->uri($uri)
            ->payload($refundRequest)
            ->post();
    }

    /**
     * @param string $paymentId
     * @param string $refundId
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed[]
     */
    public function retrieveRefund(string $paymentId, string $refundId): array
    {
        $uri = \str_replace(
            ['{id}', '{refund_id}'],
            [$paymentId, $refundId],
            Endpoints::PAYMENTS_REFUNDS_RETRIEVE
        );

        return (array) $this->request()->uri($uri)->get();
    }

    /**
     * @param string $paymentId
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function retrieveRefunds(string $paymentId): array
    {
        $uri = \str_replace('{id}', $paymentId, Endpoints::PAYMENTS_REFUNDS_RETRIEVE_ALL);

        $response = (array) $this->request()->uri($uri)->get();

        return isset($response['items']) && \is_array($response['items'])
            ? $response['items']
            : [];
    }
}
