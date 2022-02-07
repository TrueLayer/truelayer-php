<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Sdk;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierBuilderInterface;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\HppInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodBuilderInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;
use TrueLayer\Interfaces\Payment\PaymentRequestInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Interfaces\Provider\ProviderSelectionBuilderInterface;
use TrueLayer\Interfaces\UserInterface;

interface SdkInterface
{
    /**
     * @return ApiClientInterface
     */
    public function getApiClient(): ApiClientInterface;

    /**
     * @return UserInterface
     */
    public function user(): UserInterface;

    /**
     * @return AccountIdentifierBuilderInterface
     */
    public function accountIdentifier(): AccountIdentifierBuilderInterface;

    /**
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface;

    /**
     * @return PaymentMethodBuilderInterface
     */
    public function paymentMethod(): PaymentMethodBuilderInterface;

    /**
     * @return ProviderSelectionBuilderInterface
     */
    public function providerSelection(): ProviderSelectionBuilderInterface;

    /**
     * @return ProviderFilterInterface
     */
    public function providerFilter(): ProviderFilterInterface;

    /**
     * @return PaymentRequestInterface
     */
    public function payment(): PaymentRequestInterface;

    /**
     * @param string $id
     *
     *@throws ApiResponseUnsuccessfulException
     * @throws SignerException
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
     *
     * @return PaymentRetrievedInterface
     */
    public function getPayment(string $id): PaymentRetrievedInterface;

    /**
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     *
     * @return MerchantAccountInterface[]
     */
    public function getMerchantAccounts(): array;

    /**
     * @param string $id
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     *
     * @return MerchantAccountInterface
     */
    public function getMerchantAccount(string $id): MerchantAccountInterface;

    /**
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface;
}
