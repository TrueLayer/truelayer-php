<?php

declare(strict_types=1);

namespace TrueLayer\Services\Api;

use TrueLayer\Constants\Endpoints;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Contracts\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Contracts\Payment\PaymentCreatedInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\MerchantAccount\MerchantAccount;
use TrueLayer\Models\Payment\PaymentRetrieved;
use TrueLayer\Services\Util\Type;
use TrueLayer\Traits\WithSdk;

final class MerchantAccountsApi
{
    use WithSdk;

    /**
     * @return MerchantAccountInterface[]
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     */
    public function list(): array
    {
        $response = $this->getSdk()->getApiClient()->request()
            ->uri(Endpoints::MERCHANT_ACCOUNTS)
            ->get();

        $items = Type::getNullableArray($response, 'items') ?: [];

        return \array_map(fn ($data) => MerchantAccount::make($this->getSdk())->fill(
            \is_array($data) ? $data : []
        ), $items);
    }

    /**
     * @param string $id
     * @return MerchantAccountInterface
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     */
    public function retrieve(string $id): MerchantAccountInterface
    {
        $response = (array) $this->getSdk()->getApiClient()->request()
            ->uri(Endpoints::MERCHANT_ACCOUNTS . '/' . $id)
            ->get();

        return MerchantAccount::make($this->getSdk())->fill($response);
    }
}
