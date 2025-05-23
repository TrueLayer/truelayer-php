<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\SignupPlusApiInterface;

class SignupPlusApi extends Api implements SignupPlusApiInterface
{
    /**
     * @param string $paymentId
     * @param string|null $state
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function createAuthUri(string $paymentId, ?string $state): array
    {
        return (array) $this->request()
            ->uri(Endpoints::SIGNUP_PLUS_AUTH)
            ->payload([
                'payment_id' => $paymentId,
                'state' => $state,
            ])
            ->post();
    }

    /**
     * @param string $paymentId
     *
     * @throws SignerException
     * @throws ApiResponseUnsuccessfulException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function retrieveUserDataByPaymentId(string $paymentId): array
    {
        $uri = \str_replace('{id}', $paymentId, Endpoints::SIGNUP_PLUS_PAYMENTS);

        return (array) $this->request()
            ->uri($uri)
            ->get();
    }

    /**
     * @param string $mandateId
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return array|mixed[]
     */
    public function retrieveUserDataByMandateId(string $mandateId): array
    {
        $uri = \str_replace('{id}', $mandateId, Endpoints::SIGNUP_PLUS_MANDATES);

        return (array) $this->request()
            ->uri($uri)
            ->get();
    }
}
