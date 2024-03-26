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
use TrueLayer\Interfaces\RequestOptionsInterface;
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
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws SignerException
     *
     * @return PaymentRetrievedInterface
     */
    public function getPayment(string $id): PaymentRetrievedInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string                                                   $returnUri
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return AuthorizationFlowAuthorizingInterface
     *
     * @deprecated
     */
    public function startPaymentAuthorization($payment, string $returnUri): AuthorizationFlowAuthorizingInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     *
     * @throws InvalidArgumentException
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function paymentAuthorizationFlow($payment): StartAuthorizationFlowRequestInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string|ProviderInterface                                 $provider
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return AuthorizationFlowResponseInterface
     */
    public function submitPaymentProvider($payment, $provider): AuthorizationFlowResponseInterface;

    /**
     * @throws InvalidArgumentException
     *
     * @return RefundRequestInterface
     */
    public function refund(): RefundRequestInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string                                                   $refundId
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     *
     * @return RefundRetrievedInterface
     */
    public function getRefund($payment, string $refundId): RefundRetrievedInterface;

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     *
     * @return mixed[]
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
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return MerchantAccountInterface[]
     */
    public function getMerchantAccounts(): array;

    /**
     * @param string $id
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return MerchantAccountInterface
     */
    public function getMerchantAccount(string $id): MerchantAccountInterface;

    /**
     * @throws MissingHttpImplementationException
     *
     * @return WebhookInterface
     */
    public function webhook(): WebhookInterface;

    /**
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface;

    /**
     * @throws InvalidArgumentException
     *
     * @return RequestOptionsInterface
     */
    public function requestOptions(): RequestOptionsInterface;
}
