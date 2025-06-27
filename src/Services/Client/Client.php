<?php

declare(strict_types=1);

namespace TrueLayer\Services\Client;

use TrueLayer\Exceptions;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Factories\WebhookFactory;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierBuilderInterface;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\Client\ClientInterface;
use TrueLayer\Interfaces\Configuration\ClientConfigInterface;
use TrueLayer\Interfaces\Factories\ApiFactoryInterface;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
use TrueLayer\Interfaces\HppInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\Beneficiary\BeneficiaryBuilderInterface;
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
use TrueLayer\Interfaces\Payout\PayoutRetrievedInterface;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;
use TrueLayer\Interfaces\Provider\ProviderSelectionBuilderInterface;
use TrueLayer\Interfaces\Remitter\RemitterInterface;
use TrueLayer\Interfaces\Remitter\RemitterVerification\RemitterVerificationBuilderInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Interfaces\Payment\Scheme\SchemeSelectionBuilderInterface;
use TrueLayer\Interfaces\SignupPlus\SignupPlusBuilderInterface;
use TrueLayer\Interfaces\SubMerchant\PaymentSubMerchantsInterface;
use TrueLayer\Interfaces\SubMerchant\PayoutSubMerchantsInterface;
use TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyBuilderInterface;
use TrueLayer\Interfaces\UserInterface;
use TrueLayer\Interfaces\Webhook\WebhookInterface;
use TrueLayer\Interfaces\AddressInterface;
use TrueLayer\Services\Util\PaymentId;

final class Client implements ClientInterface
{
    /**
     * @var ApiClientInterface
     */
    private ApiClientInterface $apiClient;

    /**
     * @var ApiFactoryInterface
     */
    private ApiFactoryInterface $apiFactory;

    /**
     * @var EntityFactoryInterface
     */
    private EntityFactoryInterface $entityFactory;

    /**
     * @var ClientConfigInterface
     */
    private ClientConfigInterface $config;

    /**
     * @param ApiClientInterface     $apiClient
     * @param ApiFactoryInterface    $apiFactory
     * @param EntityFactoryInterface $entityFactory
     * @param ClientConfigInterface  $config
     */
    public function __construct(
        ApiClientInterface $apiClient,
        ApiFactoryInterface $apiFactory,
        EntityFactoryInterface $entityFactory,
        ClientConfigInterface $config,
    ) {
        $this->apiClient = $apiClient;
        $this->apiFactory = $apiFactory;
        $this->entityFactory = $entityFactory;
        $this->config = $config;
    }

    /**
     * @return ApiClientInterface
     */
    public function getApiClient(): ApiClientInterface
    {
        return $this->apiClient;
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     *
     * @return UserInterface
     */
    public function user(): UserInterface
    {
        return $this->entityFactory->make(UserInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     *
     * @return AccountIdentifierBuilderInterface
     */
    public function accountIdentifier(): AccountIdentifierBuilderInterface
    {
        return $this->entityFactory->make(AccountIdentifierBuilderInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     *
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface
    {
        return $this->entityFactory->make(BeneficiaryBuilderInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     *
     * @return ProviderFilterInterface
     */
    public function providerFilter(): ProviderFilterInterface
    {
        return $this->entityFactory->make(ProviderFilterInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     *
     * @return ProviderSelectionBuilderInterface
     */
    public function providerSelection(): ProviderSelectionBuilderInterface
    {
        return $this->entityFactory->make(ProviderSelectionBuilderInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return SchemeSelectionBuilderInterface
     */
    public function schemeSelection(): SchemeSelectionBuilderInterface
    {
        return $this->entityFactory->make(SchemeSelectionBuilderInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return RemitterInterface
     */
    public function remitter(): RemitterInterface
    {
        return $this->entityFactory->make(RemitterInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return RemitterVerificationBuilderInterface
     */
    public function remitterVerification(): RemitterVerificationBuilderInterface
    {
        return $this->entityFactory->make(RemitterVerificationBuilderInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     *
     * @return PaymentMethodBuilderInterface
     */
    public function paymentMethod(): PaymentMethodBuilderInterface
    {
        return $this->entityFactory->make(PaymentMethodBuilderInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     *
     * @return PaymentRequestInterface
     */
    public function payment(): PaymentRequestInterface
    {
        return $this->entityFactory->make(PaymentRequestInterface::class);
    }

    /**
     * @param string $id
     *
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return PaymentRetrievedInterface
     */
    public function getPayment(string $id): PaymentRetrievedInterface
    {
        $data = $this->apiFactory->paymentsApi()->retrieve($id);

        return $this->entityFactory->make(PaymentRetrievedInterface::class, $data);
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string                                                   $returnUri
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return AuthorizationFlowAuthorizingInterface
     *
     * @deprecated
     */
    public function startPaymentAuthorization($payment, string $returnUri): AuthorizationFlowAuthorizingInterface
    {
        $paymentId = PaymentId::find($payment);
        $data = $this->apiFactory->paymentsApi()->startAuthorizationFlow($paymentId, [
            'provider_selection' => (object) [],
            'redirect' => ['return_uri' => $returnUri],
        ]);

        return $this->entityFactory->make(AuthorizationFlowAuthorizingInterface::class, $data);
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     *
     * @throws InvalidArgumentException
     *
     * @return StartAuthorizationFlowRequestInterface
     */
    public function paymentAuthorizationFlow($payment): StartAuthorizationFlowRequestInterface
    {
        return $this->entityFactory->make(StartAuthorizationFlowRequestInterface::class)
            ->paymentId(PaymentId::find($payment));
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string|ProviderInterface                                 $provider
     *
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return AuthorizationFlowResponseInterface
     */
    public function submitPaymentProvider($payment, $provider): AuthorizationFlowResponseInterface
    {
        $paymentId = PaymentId::find($payment);

        if ($provider instanceof ProviderInterface) {
            $provider = $provider->getId();
        }

        if (!\is_string($provider)) {
            throw new InvalidArgumentException('Provider must be string|ProviderInterface');
        }

        $data = $this->apiFactory->paymentsApi()->submitProvider($paymentId, $provider);

        return $this->entityFactory->make(AuthorizationFlowResponseInterface::class, $data);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return RefundRequestInterface
     */
    public function refund(): RefundRequestInterface
    {
        return $this->entityFactory->make(RefundRequestInterface::class);
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string                                                   $refundId
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return RefundRetrievedInterface
     */
    public function getRefund($payment, string $refundId): RefundRetrievedInterface
    {
        $paymentId = PaymentId::find($payment);

        $data = $this->apiFactory->paymentsApi()->retrieveRefund($paymentId, $refundId);

        return $this->entityFactory->make(RefundRetrievedInterface::class, $data);
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     *
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     *
     * @return mixed[]
     */
    public function getRefunds($payment): array
    {
        $paymentId = PaymentId::find($payment);

        $data = $this->apiFactory->paymentsApi()->retrieveRefunds($paymentId);

        return $this->entityFactory->makeMany(RefundRetrievedInterface::class, $data);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function hostedPaymentsPage(): HppInterface
    {
        return $this->entityFactory->make(HppInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return Payout\PayoutRequestInterface
     */
    public function payout(): Payout\PayoutRequestInterface
    {
        return $this->entityFactory->make(Payout\PayoutRequestInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return Payout\Beneficiary\BeneficiaryBuilderInterface
     */
    public function payoutBeneficiary(): Payout\Beneficiary\BeneficiaryBuilderInterface
    {
        return $this->entityFactory->make(Payout\Beneficiary\BeneficiaryBuilderInterface::class);
    }

    public function payoutSchemeSelection(): Payout\Scheme\SchemeSelectionBuilderInterface
    {
        return $this->entityFactory->make(Payout\Scheme\SchemeSelectionBuilderInterface::class);
    }

    public function getPayout(string $id): PayoutRetrievedInterface
    {
        $data = $this->apiFactory->payoutsApi()->retrieve($id);

        return $this->entityFactory->make(PayoutRetrievedInterface::class, $data);
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return MerchantAccountInterface[]
     */
    public function getMerchantAccounts(): array
    {
        $data = $this->apiFactory->merchantAccountsApi()->list();

        return $this->entityFactory->makeMany(MerchantAccountInterface::class, $data);
    }

    /**
     * @param string $id
     *
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return MerchantAccountInterface
     */
    public function getMerchantAccount(string $id): MerchantAccountInterface
    {
        $data = $this->apiFactory->merchantAccountsApi()->retrieve($id);

        return $this->entityFactory->make(MerchantAccountInterface::class, $data);
    }

    /**
     * @throws Exceptions\MissingHttpImplementationException
     *
     * @return WebhookInterface
     */
    public function webhook(): WebhookInterface
    {
        return (new WebhookFactory())->make($this->config);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return RequestOptionsInterface
     */
    public function requestOptions(): RequestOptionsInterface
    {
        return $this->entityFactory->make(RequestOptionsInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return SignupPlusBuilderInterface
     */
    public function signupPlus(): SignupPlusBuilderInterface
    {
        return $this->entityFactory->make(SignupPlusBuilderInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return AddressInterface
     */
    public function address(): AddressInterface
    {
        return $this->entityFactory->make(AddressInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return UltimateCounterpartyBuilderInterface
     */
    public function ultimateCounterparty(): UltimateCounterpartyBuilderInterface
    {
        return $this->entityFactory->make(UltimateCounterpartyBuilderInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return PaymentSubMerchantsInterface
     */
    public function paymentSubMerchants(): PaymentSubMerchantsInterface
    {
        return $this->entityFactory->make(PaymentSubMerchantsInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return PayoutSubMerchantsInterface
     */
    public function payoutSubMerchants(): PayoutSubMerchantsInterface
    {
        return $this->entityFactory->make(PayoutSubMerchantsInterface::class);
    }
}
