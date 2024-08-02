<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Client;

use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\MissingHttpImplementationException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierBuilderInterface;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\HppInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentRequestInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\Payment\RefundRequestInterface;
use TrueLayer\Interfaces\Payment\RefundRetrievedInterface;
use TrueLayer\Interfaces\Payment\StartAuthorizationFlowRequestInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodBuilderInterface;
use TrueLayer\Interfaces\Payout;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;
use TrueLayer\Interfaces\Provider\ProviderSelectionBuilderInterface;
use TrueLayer\Interfaces\Remitter\RemitterInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Interfaces\Scheme\SchemeSelectionBuilderInterface;
use TrueLayer\Interfaces\UserInterface;
use TrueLayer\Interfaces\Webhook\WebhookInterface;

interface ClientInterface
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
     * @return SchemeSelectionBuilderInterface
     */
    public function schemeSelection(): SchemeSelectionBuilderInterface;

    /**
     * @return RemitterInterface
     */
    public function remitter(): RemitterInterface;

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
     * @return PaymentRetrievedInterface
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @throws ApiRequestJsonSerializationException
     */
    public function getPayment(string $id): PaymentRetrievedInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string $returnUri
     *
     * @return AuthorizationFlowAuthorizingInterface
     *
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     * @deprecated
     */
    public function startPaymentAuthorization($payment, string $returnUri): AuthorizationFlowAuthorizingInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     *
     * @return StartAuthorizationFlowRequestInterface
     * @throws InvalidArgumentException
     *
     */
    public function paymentAuthorizationFlow($payment): StartAuthorizationFlowRequestInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string|ProviderInterface $provider
     *
     * @return AuthorizationFlowResponseInterface
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function submitPaymentProvider($payment, $provider): AuthorizationFlowResponseInterface;

    /**
     * @return RefundRequestInterface
     * @throws InvalidArgumentException
     *
     */
    public function refund(): RefundRequestInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string $refundId
     *
     * @return RefundRetrievedInterface
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     *
     * @throws ApiRequestJsonSerializationException
     */
    public function getRefund($payment, string $refundId): RefundRetrievedInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     *
     * @return mixed[]
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     *
     * @throws ApiRequestJsonSerializationException
     */
    public function getRefunds($payment): array;

    /**
     * @return Payout\PayoutRequestInterface
     */
    public function payout(): Payout\PayoutRequestInterface;

    /**
     * @return Payout\BeneficiaryBuilderInterface
     */
    public function payoutBeneficiary(): Payout\BeneficiaryBuilderInterface;

    /**
     * @param string $id
     *
     * @return Payout\PayoutRetrievedInterface
     */
    public function getPayout(string $id): Payout\PayoutRetrievedInterface;

    /**
     * @return MerchantAccountInterface[]
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function getMerchantAccounts(): array;

    /**
     * @param string $id
     *
     * @return MerchantAccountInterface
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @throws ApiResponseUnsuccessfulException
     */
    public function getMerchantAccount(string $id): MerchantAccountInterface;

    /**
     * @return WebhookInterface
     * @throws MissingHttpImplementationException
     *
     */
    public function webhook(): WebhookInterface;

    /**
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface;

    /**
     * @return RequestOptionsInterface
     * @throws InvalidArgumentException
     *
     */
    public function requestOptions(): RequestOptionsInterface;
}
