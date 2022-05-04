<?php

declare(strict_types=1);

namespace TrueLayer\Services\Client;

use TrueLayer\Exceptions;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierBuilderInterface;
use TrueLayer\Interfaces\ApiClient\ApiClientInterface;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Client\ClientInterface;
use TrueLayer\Interfaces\Factories\ApiFactoryInterface;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
use TrueLayer\Interfaces\HppInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\Payment\PaymentRequestInterface;
use TrueLayer\Interfaces\Payment\PaymentRetrievedInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodBuilderInterface;
use TrueLayer\Interfaces\Payout;
use TrueLayer\Interfaces\Payout\PayoutRetrievedInterface;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Interfaces\Provider\ProviderInterface;
use TrueLayer\Interfaces\Provider\ProviderSelectionBuilderInterface;
use TrueLayer\Interfaces\UserInterface;

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
     * @param ApiClientInterface     $apiClient
     * @param ApiFactoryInterface    $apiFactory
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(
        ApiClientInterface $apiClient,
        ApiFactoryInterface $apiFactory,
        EntityFactoryInterface $entityFactory
    ) {
        $this->apiClient = $apiClient;
        $this->apiFactory = $apiFactory;
        $this->entityFactory = $entityFactory;
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
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
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
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return AuthorizationFlowAuthorizingInterface
     */
    public function startPaymentAuthorization($payment, string $returnUri): AuthorizationFlowAuthorizingInterface
    {
        $paymentId = $this->getPaymentId($payment);
        $data = $this->apiFactory->paymentsApi()->startAuthorizationFlow($paymentId, $returnUri);

        return $this->entityFactory->make(AuthorizationFlowAuthorizingInterface::class, $data);
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     * @param string|ProviderInterface                                 $provider
     *
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @return AuthorizationFlowResponseInterface
     */
    public function submitPaymentProvider($payment, $provider): AuthorizationFlowResponseInterface
    {
        $paymentId = $this->getPaymentId($payment);

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
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\ValidationException
     *
     * @return HppInterface
     */
    public function hostedPaymentsPage(): HppInterface
    {
        return $this->entityFactory->make(HppInterface::class);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     *
     * @return MerchantAccountInterface[]
     */
    public function getMerchantAccounts(): array
    {
        $data = $this->apiFactory->merchantAccountsApi()->list();

        return $this->entityFactory->makeMany(MerchantAccountInterface::class, $data);
    }

    /**
     * @throws ValidationException
     * @throws InvalidArgumentException
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
     * @param string $id
     *
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\SignerException
     * @throws Exceptions\ValidationException
     * @throws Exceptions\ApiRequestJsonSerializationException
     * @throws Exceptions\ApiResponseUnsuccessfulException
     *
     * @return MerchantAccountInterface
     */
    public function getMerchantAccount(string $id): MerchantAccountInterface
    {
        $data = $this->apiFactory->merchantAccountsApi()->retrieve($id);

        return $this->entityFactory->make(MerchantAccountInterface::class, $data);
    }

    /**
     * @param string|PaymentCreatedInterface|PaymentRetrievedInterface $payment
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    private function getPaymentId($payment): string
    {
        if ($payment instanceof PaymentCreatedInterface || $payment instanceof PaymentRetrievedInterface) {
            return $payment->getId();
        }

        if (\is_string($payment)) {
            return $payment;
        }

        // @phpstan-ignore-next-line
        throw new InvalidArgumentException('Payment must be string|PaymentCreatedInterface|PaymentRetrievedInterface');
    }
}
