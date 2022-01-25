<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\PaymentsApiInterface;

final class PaymentsApi extends Api implements PaymentsApiInterface
{
    /**
     * @param mixed[] $paymentRequest
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function create(array $paymentRequest): array
    {
        return (array) $this->request()
            ->uri(Endpoints::PAYMENTS)
            ->payload($paymentRequest)
            ->post();
    }

    /**
     * @param string $id
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function retrieve(string $id): array
    {
        return (array) $this->request()
            ->uri(Endpoints::PAYMENTS . '/' . $id)
            ->get();
    }
}
