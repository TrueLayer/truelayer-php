<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Api;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;

interface PaymentsApiInterface
{
    /**
     * @param mixed[] $paymentRequest
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed[]
     */
    public function create(array $paymentRequest): array;

    /**
     * @param string $id
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function retrieve(string $id): array;

    /**
     * @param string $id
     * @param string $returnUri
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function startAuthorizationFlow(string $id, string $returnUri): array;

    /**
     * @param string $id
     * @param string $providerId
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function submitProvider(string $id, string $providerId): array;

    /**
     * @param string  $paymentId
     * @param mixed[] $refundRequest
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function createRefund(string $paymentId, array $refundRequest): array;

    /**
     * @param string $paymentId
     * @param string $refundId
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function retrieveRefund(string $paymentId, string $refundId): array;

    /**
     * @param string $paymentId
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function retrieveRefunds(string $paymentId): array;
}
