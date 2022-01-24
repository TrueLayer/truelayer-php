<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Interfaces\Api\PaymentsApiInterface;
use TrueLayer\Interfaces\Payment\PaymentRequestInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;

final class PaymentsApi extends Api implements PaymentsApiInterface
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
    public function create(array $paymentRequest): array
    {
        return (array) $this->request()
            ->uri(Endpoints::PAYMENTS)
            ->payload($paymentRequest)
            ->post();
    }

    /**
     * @param string $id
     * @return array
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     */
    public function retrieve(string $id): array
    {
        return (array) $this->request()
            ->uri(Endpoints::PAYMENTS . '/' . $id)
            ->get();
    }
}
