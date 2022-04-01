<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\PayoutsApiInterface;

final class PayoutsApi extends Api implements PayoutsApiInterface
{
    /**
     * @param mixed[] $payoutRequest
     *
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return mixed[]
     */
    public function create(array $payoutRequest): array
    {
        return (array) $this->request()
            ->uri(Endpoints::PAYOUTS_CREATE)
            ->payload($payoutRequest)
            ->post();
    }

    /**
     * @param string $id
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function retrieve(string $id): array
    {
        $uri = \str_replace('{id}', $id, Endpoints::PAYOUTS_RETRIEVE);

        return (array) $this->request()->uri($uri)->get();
    }
}
