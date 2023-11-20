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
     * @param mixed[] $paymentRequest
     * @param RequestOptionsInterface|null $requestOptions
     * @return mixed[]
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     */
    public function create(array $paymentRequest, ?RequestOptionsInterface $requestOptions): array;

    /**
     * @param string $id
     *
     * @return mixed[]
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function retrieve(string $id): array;

    /**
     * @param string $id
     * @param string $returnUri
     *
     * @return mixed[]
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function startAuthorizationFlow(string $id, string $returnUri): array;

    /**
     * @param string $id
     * @param string $providerId
     *
     * @return mixed[]
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function submitProvider(string $id, string $providerId): array;

    /**
     * @param string $paymentId
     * @param mixed[] $refundRequest
     * @param RequestOptionsInterface|null $requestOptions
     * @return mixed[]
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     */
    public function createRefund(string $paymentId, array $refundRequest, ?RequestOptionsInterface $requestOptions): array;

    /**
     * @param string $paymentId
     * @param string $refundId
     *
     * @return mixed[]
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function retrieveRefund(string $paymentId, string $refundId): array;

    /**
     * @param string $paymentId
     *
     * @return mixed[]
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @throws ApiRequestJsonSerializationException
     */
    public function retrieveRefunds(string $paymentId): array;
}
