<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\Api\MerchantAccountsApiInterface;
use TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountTransactionRetrievedInterface;

final class MerchantAccountsApi extends Api implements MerchantAccountsApiInterface
{
    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function list(): array
    {
        $response = (array) $this->request()
            ->uri(Endpoints::MERCHANT_ACCOUNTS)
            ->get();

        return isset($response['items']) && \is_array($response['items'])
            ? $response['items']
            : [];
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
            ->uri(Endpoints::MERCHANT_ACCOUNTS . '/' . $id)
            ->get();
    }

    /**
     * @param string $merchantAccountId
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     *
     * @return MerchantAccountTransactionRetrievedInterface[]
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     */
    public function listTransactions(string $merchantAccountId, \DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $uri = "{$merchantAccountId}/transactions?from={$from->format(\DateTimeInterface::ATOM)}&to={$to->format(\DateTimeInterface::ATOM)}";

        $response = (array) $this->request()
            ->uri(Endpoints::MERCHANT_ACCOUNTS . '/' . $uri)
            ->get();

        return isset($response['items']) && \is_array($response['items'])
            ? $response['items']
            : [];
    }
}
