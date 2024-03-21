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
use TrueLayer\Interfaces\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Client\ClientInterface;
use TrueLayer\Interfaces\Configuration\ClientConfigInterface;
use TrueLayer\Interfaces\Factories\ApiFactoryInterface;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
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
use TrueLayer\Interfaces\Payout\PayoutRetrievedInterface;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;
use TrueLayer\Interfaces\Provider\ProviderSelectionBuilderInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Interfaces\UserInterface;
use TrueLayer\Interfaces\Webhook\WebhookInterface;
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
     * @param ApiClientInterface $apiClient
     * @param ApiFactoryInterface $apiFactory
     * @param EntityFactoryInterface $entityFactory
     * @param ClientConfigInterface $config
     */
    public function __construct(
        ApiClientInterface     $apiClient,
        ApiFactoryInterface    $apiFactory,
        EntityFactoryInterface $entityFactory,
        ClientConfigInterface  $config
    )
    {
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
     * @return UserInterface
     * @throws Exceptions\InvalidArgumentException
     *
     * @throws Exceptions\InvalidArgumentException
     */
    public function user(): UserInterface
    {
        return $this->entityFactory->make(UserInterface::class);
    }

    /**
     * @return AccountIdentifierBuilderInterface
     * @throws Exceptions\InvalidArgumentException
     *
     * @throws Exceptions\InvalidArgumentException
     */
    public function accountIdentifier(): AccountIdentifierBuilderInterface
    {
        return $this->entityFactory->make(AccountIdentifierBuilderInterface::class);
    }

    /**
     * @return BeneficiaryBuilderInterface
     * @throws Exceptions\InvalidArgumentException
     *
     * @throws Exceptions\InvalidArgumentException
     */
    public function beneficiary(): BeneficiaryBuilderInterface
    {
        return $this->entityFactory->make(BeneficiaryBuilderInterface::class);
    }

    /**
     * @return ProviderFilterInterface
     * @throws Exceptions\InvalidArgumentException
     *
     * @throws Exceptions\InvalidArgumentException
     */
    public function providerFilter(): ProviderFilterInterface
    {
        return $this->entityFactory->make(ProviderFilterInterface::class);
    }

    /**
     * @return ProviderSelectionBuilderInterface
     * @throws Exceptions\InvalidArgumentException
     *
     * @throws Exceptions\InvalidArgumentException
     */
    public function providerSelection(): ProviderSelectionBuilderInterface
    {
        return $this->entityFactory->make(ProviderSelectionBuilderInterface::class);
    }

    /**
     * @return PaymentMethodBuilderInterface
     * @throws Exceptions\InvalidArgumentException
     *
     * @throws Exceptions\InvalidArgumentException
     */
    public function paymentMethod(): PaymentMethodBuilderInterface
    {
        return $this->entityFactory->make(PaymentMethodBuilderInterface::class);
    }

    /**
     * @return PaymentRequestInterface
     * @throws Exceptions\InvalidArgumentException
     *
     * @throws Exceptions\InvalidArgumentException
     */
    public function payment(): PaymentRequestInterface
    {
        return $this->entityFactory->make(PaymentRequestInterface::class);
    }

    /**
     * @param string $id
     *
     * @return PaymentRetrievedInterface
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     *
     * @throws Exceptions\ApiRequestJsonSerializationException
     */
    public function getPayment(string $id): PaymentRetrievedInterface
    {
        $data = $this->apiFactory->paymentsApi()->retrieve($id);

        return $this->entityFactory->make(PaymentRetrievedInterface::class, $data);
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string $returnUri
     *
     * @return AuthorizationFlowAuthorizingInterface
     *
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws SignerException
     *
     * @deprecated
     */
    public function startPaymentAuthorization($payment, string $returnUri): AuthorizationFlowAuthorizingInterface
    {
        $paymentId = PaymentId::find($payment);
        $data = $this->apiFactory->paymentsApi()->startAuthorizationFlow($paymentId, [
            'provider_selection' => (object)[],
            'redirect' => ['return_uri' => $returnUri],
        ]);

        return $this->entityFactory->make(AuthorizationFlowAuthorizingInterface::class, $data);
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     *
     * @return StartAuthorizationFlowRequestInterface
     *
     * @throws InvalidArgumentException
     */
    public function paymentAuthorizationFlow($payment): StartAuthorizationFlowRequestInterface
    {
        return $this->entityFactory->make(StartAuthorizationFlowRequestInterface::class)
            ->paymentId(PaymentId::find($payment));
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string|ProviderInterface $provider
     *
     * @return AuthorizationFlowResponseInterface
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws SignerException
     *
     * @throws ApiRequestJsonSerializationException
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
     * @return RefundRequestInterface
     * @throws InvalidArgumentException
     */
    public function refund(): RefundRequestInterface
    {
        return $this->entityFactory->make(RefundRequestInterface::class);
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string $refundId
     *
     * @return RefundRetrievedInterface
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     *
     * @throws SignerException
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
     * @return mixed[]
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     *
     * @throws SignerException
     */
    public function getRefunds($payment): array
    {
        $paymentId = PaymentId::find($payment);

        $data = $this->apiFactory->paymentsApi()->retrieveRefunds($paymentId);

        return $this->entityFactory->makeMany(RefundRetrievedInterface::class, $data);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidArgumentException
     */
    public function hostedPaymentsPage(): HppInterface
    {
        return $this->entityFactory->make(HppInterface::class);
    }

    /**
     * @return Payout\PayoutRequestInterface
     * @throws InvalidArgumentException
     */
    public function payout(): Payout\PayoutRequestInterface
    {
        return $this->entityFactory->make(Payout\PayoutRequestInterface::class);
    }

    /**
     * @return Payout\BeneficiaryBuilderInterface
     * @throws InvalidArgumentException
     */
    public function payoutBeneficiary(): Payout\BeneficiaryBuilderInterface
    {
        return $this->entityFactory->make(Payout\BeneficiaryBuilderInterface::class);
    }

    public function getPayout(string $id): PayoutRetrievedInterface
    {
        $data = $this->apiFactory->payoutsApi()->retrieve($id);

        return $this->entityFactory->make(PayoutRetrievedInterface::class, $data);
    }

    /**
     * @return MerchantAccountInterface[]
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     *
     * @throws Exceptions\ApiRequestJsonSerializationException
     */
    public function getMerchantAccounts(): array
    {
        $data = $this->apiFactory->merchantAccountsApi()->list();

        return $this->entityFactory->makeMany(MerchantAccountInterface::class, $data);
    }

    /**
     * @param string $id
     *
     * @return MerchantAccountInterface
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     *
     * @throws Exceptions\ApiRequestJsonSerializationException
     */
    public function getMerchantAccount(string $id): MerchantAccountInterface
    {
        $data = $this->apiFactory->merchantAccountsApi()->retrieve($id);

        return $this->entityFactory->make(MerchantAccountInterface::class, $data);
    }

    /**
     * @return WebhookInterface
     * @throws Exceptions\MissingHttpImplementationException
     *
     */
    public function webhook(): WebhookInterface
    {
        return (new WebhookFactory())->make($this->config);
    }

    /**
     * @return RequestOptionsInterface
     *
     * @throws InvalidArgumentException
     */
    public function requestOptions(): RequestOptionsInterface
    {
        return $this->entityFactory->make(RequestOptionsInterface::class);
    }
}
