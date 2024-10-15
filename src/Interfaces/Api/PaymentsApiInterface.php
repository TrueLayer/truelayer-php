<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Api;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\RequestOptionsInterface;

interface PaymentsApiInterface
{
    /**
     * @param mixed[]                      $paymentRequest
     * @param RequestOptionsInterface|null $requestOptions
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed[]
     */
    public function create(array $paymentRequest, ?RequestOptionsInterface $requestOptions): array;

    /**
     * @param string $id
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed[]
     */
    public function retrieve(string $id): array;

    /**
     * @param string $id
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return void
     */
    public function cancel(string $id): void;

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
    public function startAuthorizationFlow(string $id, array $authorizationFlowRequest): array;

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
    public function submitProvider(string $id, string $providerId): array;

    /**
     * @param string                       $paymentId
     * @param mixed[]                      $refundRequest
     * @param RequestOptionsInterface|null $requestOptions
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function createRefund(string $paymentId, array $refundRequest, ?RequestOptionsInterface $requestOptions): array;

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
    public function retrieveRefund(string $paymentId, string $refundId): array;

    /**
     * @param string $paymentId
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function retrieveRefunds(string $paymentId): array;
}
