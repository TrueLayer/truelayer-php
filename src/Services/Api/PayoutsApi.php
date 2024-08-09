<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\PayoutsApiInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;

final class PayoutsApi extends Api implements PayoutsApiInterface
{
    /**
     * @param mixed[]                      $payoutRequest
     * @param RequestOptionsInterface|null $requestOptions
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function create(array $payoutRequest, ?RequestOptionsInterface $requestOptions = null): array
    {
        return (array) $this->request()
            ->requestOptions($requestOptions)
            ->uri(Endpoints::PAYOUTS_CREATE)
            ->payload($payoutRequest)
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
        $uri = \str_replace('{id}', $id, Endpoints::PAYOUTS_RETRIEVE);

        return (array) $this->request()->uri($uri)->get();
    }
}
