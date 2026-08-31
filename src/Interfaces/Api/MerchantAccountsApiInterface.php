<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Api;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountTransactionRetrievedInterface;

interface MerchantAccountsApiInterface
{
    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return MerchantAccountInterface[]
     */
    public function list(): array;

    /**
     * @param string $id
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return mixed[]
     */
    public function retrieve(string $id): array;

    /**
     * @param string $merchantAccountId
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     *
     * @return MerchantAccountTransactionRetrievedInterface[]
     */
    public function listTransactions(string $merchantAccountId, \DateTimeInterface $from, \DateTimeInterface $to): array;
}
