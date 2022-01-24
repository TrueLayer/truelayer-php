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
     * @throws ApiResponseUnsuccessfulException

     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return array
     */
    public function create(array $paymentRequest): array;

    /**
     * @param string $id
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     */
    public function retrieve(string $id): array;
}
