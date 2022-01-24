<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\MerchantAccountsApiInterface;

final class MerchantAccountsApi extends Api implements MerchantAccountsApiInterface
{
    /**
     * @return mixed[]
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     */
    public function list(): array
    {
        $response = (array) $this->request()
            ->uri(Endpoints::MERCHANT_ACCOUNTS)
            ->get();

        return isset($response['items']) && is_array($response['items'])
            ? $response['items']
            : [];
    }

    /**
     * @param string $id
     * @return mixed[]
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     */
    public function retrieve(string $id): array
    {
        return (array) $this->request()
            ->uri(Endpoints::MERCHANT_ACCOUNTS . '/' . $id)
            ->get();
    }
}
