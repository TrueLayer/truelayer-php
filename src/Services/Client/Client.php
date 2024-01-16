<?php

declare(strict_types=1);

namespace TrueLayer\Services\Client;

use TrueLayer\Exceptions;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
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
     * @param ApiClientInterface     $apiClient
     * @param ApiFactoryInterface    $apiFactory
     * @param EntityFactoryInterface $entityFactory
     * @param ClientConfigInterface  $config
     */
    public function __construct(
        ApiClientInterface $apiClient,
        ApiFactoryInterface $apiFactory,
        EntityFactoryInterface $entityFactory,
        ClientConfigInterface $config
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
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\InvalidArgumentException
     *
     * @return UserInterface
     */
    public function user(): UserInterface
    {
        return $this->entityFactory->make(UserInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\InvalidArgumentException
     *
     * @return AccountIdentifierBuilderInterface
     */
    public function accountIdentifier(): AccountIdentifierBuilderInterface
    {
        return $this->entityFactory->make(AccountIdentifierBuilderInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\InvalidArgumentException
     *
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface
    {
        return $this->entityFactory->make(BeneficiaryBuilderInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\InvalidArgumentException
     *
     * @return ProviderFilterInterface
     */
    public function providerFilter(): ProviderFilterInterface
    {
        return $this->entityFactory->make(ProviderFilterInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\InvalidArgumentException
     *
     * @return ProviderSelectionBuilderInterface
     */
    public function providerSelection(): ProviderSelectionBuilderInterface
    {
        return $this->entityFactory->make(ProviderSelectionBuilderInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\InvalidArgumentException
     *
     * @return PaymentMethodBuilderInterface
     */
    public function paymentMethod(): PaymentMethodBuilderInterface
    {
        return $this->entityFactory->make(PaymentMethodBuilderInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\InvalidArgumentException
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
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     * @throws Exceptions\ValidationException
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
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
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
     * @throws ValidationException
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
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
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
     * @throws ValidationException
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
     * @throws SignerException
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
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
     * @throws SignerException
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
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
     * @throws Exceptions\ValidationException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidArgumentException
     */
    public function hostedPaymentsPage(): HppInterface
    {
        return $this->entityFactory->make(HppInterface::class);
    }

    /**
     * @throws ValidationException
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return Payout\PayoutRequestInterface
     */
    public function payout(): Payout\PayoutRequestInterface
    {
        return $this->entityFactory->make(Payout\PayoutRequestInterface::class);
    }

    /**
     * @throws ValidationException
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return Payout\BeneficiaryBuilderInterface
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
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     * @throws Exceptions\ValidationException
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
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     * @throws Exceptions\ValidationException
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
     * @throws ValidationException
     *
     * @return RequestOptionsInterface
     */
    public function requestOptions(): RequestOptionsInterface
    {
        return $this->entityFactory->make(RequestOptionsInterface::class);
    }
}
