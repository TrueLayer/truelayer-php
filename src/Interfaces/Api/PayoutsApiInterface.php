<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Api;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\RequestOptionsInterface;

interface PayoutsApiInterface
{
    /**
     * @param mixed[] $payoutRequest
     * @param RequestOptionsInterface|null $requestOptions
     * @return mixed[]
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     */
    public function create(array $payoutRequest, ?RequestOptionsInterface $requestOptions): array;

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
}
