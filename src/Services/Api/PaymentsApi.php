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
            ->uri(Endpoints::PAYMENTS_CREATE)
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
        $uri = \str_replace('{id}', $id, Endpoints::PAYMENTS_RETRIEVE);

        return (array) $this->request()->uri($uri)->get();
    }

    /**
     * @param string $id
     * @param string $returnUri
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function startAuthorizationFlow(string $id, string $returnUri): array
    {
        $uri = \str_replace('{id}', $id, Endpoints::PAYMENTS_START_AUTH_FLOW);

        return (array) $this->request()
            ->uri($uri)
            ->payload([
                'provider_selection' => (object) [],
                'redirect' => ['return_uri' => $returnUri],
            ])
            ->post();
    }

    /**
     * @param string $id
     * @param string $providerId
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
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
}
