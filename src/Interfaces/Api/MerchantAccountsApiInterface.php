<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Api;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;

interface MerchantAccountsApiInterface
{
    /**
     * @return MerchantAccountInterface[]
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     */
    public function list(): array;

    /**
     * @param string $id
     * @return mixed[]
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     */
    public function retrieve(string $id): array;
}
