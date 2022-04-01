<?php

declare(strict_types=1);

namespace TrueLayer\Factories;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\Arr;
use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\AuthorizationFlowStatusTypes;
use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\Endpoints;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Constants\PayoutStatus;
use TrueLayer\Entities;
use TrueLayer\Entities\Hpp;
use TrueLayer\Entities\User;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces;

final class EntityFactory implements Interfaces\Factories\EntityFactoryInterface
{
    /**
     * @var ValidatorFactory
     */
    private ValidatorFactory $validatorFactory;

    /**
     * @var Interfaces\Factories\ApiFactoryInterface
     */
    private Interfaces\Factories\ApiFactoryInterface $apiFactory;

    /**
     * @var Interfaces\Client\ConfigInterface
     */
    private Interfaces\Client\ConfigInterface $sdkConfig;

    /**
     * @param ValidatorFactory $validatorFactory
     * @param Interfaces\Factories\ApiFactoryInterface $apiFactory
     * @param Interfaces\Client\ConfigInterface $sdkConfig
     */
    public function __construct(
        ValidatorFactory                         $validatorFactory,
        Interfaces\Factories\ApiFactoryInterface $apiFactory,
        Interfaces\Client\ConfigInterface        $sdkConfig)
    {
        $this->validatorFactory = $validatorFactory;
        $this->apiFactory = $apiFactory;
        $this->sdkConfig = $sdkConfig;
    }

    private const BINDINGS = [
        Interfaces\UserInterface::class => User::class,
        Interfaces\HppInterface::class => 'makeHpp',

        Interfaces\Beneficiary\BeneficiaryBuilderInterface::class => Entities\Beneficiary\BeneficiaryBuilder::class,
        Interfaces\Beneficiary\MerchantBeneficiaryInterface::class => Entities\Beneficiary\MerchantBeneficiary::class,
        Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface::class => Entities\Beneficiary\ExternalAccountBeneficiary::class,

        Interfaces\Payment\PaymentRequestInterface::class => Entities\Payment\PaymentRequest::class,
        Interfaces\Payment\PaymentCreatedInterface::class => Entities\Payment\PaymentCreated::class,
        Interfaces\Payment\PaymentAuthorizationRequiredInterface::class => Entities\Payment\PaymentRetrieved\PaymentAuthorizationRequired::class,
        Interfaces\Payment\PaymentAuthorizingInterface::class => Entities\Payment\PaymentRetrieved\PaymentAuthorizing::class,
        Interfaces\Payment\PaymentAuthorizedInterface::class => Entities\Payment\PaymentRetrieved\PaymentAuthorized::class,
        Interfaces\Payment\PaymentExecutedInterface::class => Entities\Payment\PaymentRetrieved\PaymentExecuted::class,
        Interfaces\Payment\PaymentSettledInterface::class => Entities\Payment\PaymentRetrieved\PaymentSettled::class,
        Interfaces\Payment\PaymentFailedInterface::class => Entities\Payment\PaymentRetrieved\PaymentFailed::class,
        Interfaces\Payment\PaymentSourceInterface::class => Entities\Payment\PaymentRetrieved\PaymentSource::class,

        Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface::class => Entities\Payment\AuthorizationFlow\AuthorizationFlow::class,
        Interfaces\Payment\AuthorizationFlow\ConfigurationInterface::class => Entities\Payment\AuthorizationFlow\Configuration::class,
        Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface::class => Entities\Payment\AuthorizationFlow\Action\ProviderSelectionAction::class,
        Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface::class => Entities\Payment\AuthorizationFlow\Action\RedirectAction::class,
        Interfaces\Payment\AuthorizationFlow\Action\WaitActionInterface::class => Entities\Payment\AuthorizationFlow\Action\WaitAction::class,
        Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface::class => Entities\Payment\AuthorizationFlow\AuthorizationFlowAuthorizing::class,
        Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailedInterface::class => Entities\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailed::class,

        Interfaces\PaymentMethod\PaymentMethodBuilderInterface::class => Entities\Payment\PaymentMethod\PaymentMethodBuilder::class,
        Interfaces\PaymentMethod\BankTransferPaymentMethodInterface::class => Entities\Payment\PaymentMethod\BankTransferPaymentMethod::class,

        Interfaces\Provider\ProviderSelectionBuilderInterface::class => Entities\Provider\ProviderSelection\ProviderSelectionBuilder::class,
        Interfaces\Provider\UserSelectedProviderSelectionInterface::class => Entities\Provider\ProviderSelection\UserSelectedProviderSelection::class,
        Interfaces\Provider\ProviderInterface::class => Entities\Provider\Provider::class,
        Interfaces\Provider\ProviderFilterInterface::class => Entities\Provider\ProviderSelection\ProviderFilter::class,

        Interfaces\AccountIdentifier\AccountIdentifierBuilderInterface::class => Entities\AccountIdentifier\AccountIdentifierBuilder::class,
        Interfaces\AccountIdentifier\ScanInterface::class => Entities\AccountIdentifier\Scan::class,
        Interfaces\AccountIdentifier\ScanDetailsInterface::class => Entities\AccountIdentifier\Iban::class,
        Interfaces\AccountIdentifier\IbanInterface::class => Entities\AccountIdentifier\Iban::class,
        Interfaces\AccountIdentifier\IbanDetailsInterface::class => Entities\AccountIdentifier\Iban::class,
        Interfaces\AccountIdentifier\BbanInterface::class => Entities\AccountIdentifier\Bban::class,
        Interfaces\AccountIdentifier\BbanDetailsInterface::class => Entities\AccountIdentifier\Bban::class,
        Interfaces\AccountIdentifier\NrbInterface::class => Entities\AccountIdentifier\Nrb::class,
        Interfaces\AccountIdentifier\NrbDetailsInterface::class => Entities\AccountIdentifier\Nrb::class,

        Interfaces\Payout\BeneficiaryBuilderInterface::class => Entities\Payout\BeneficiaryBuilder::class,
        Interfaces\Payout\PaymentSourceBeneficiaryInterface::class => Entities\Payout\PaymentSourceBeneficiary::class,
        Interfaces\Payout\PayoutCreatedInterface::class => Entities\Payout\PayoutCreated::class,
        Interfaces\Payout\PayoutRequestInterface::class => Entities\Payout\PayoutRequest::class,
        Interfaces\Payout\PayoutPendingInterface::class => Entities\Payout\PayoutRetrieved\PayoutPending::class,
        Interfaces\Payout\PayoutAuthorizedInterface::class => Entities\Payout\PayoutRetrieved\PayoutAuthorized::class,
        Interfaces\Payout\PayoutExecutedInterface::class => Entities\Payout\PayoutRetrieved\PayoutExecuted::class,
        Interfaces\Payout\PayoutFailedInterface::class => Entities\Payout\PayoutRetrieved\PayoutFailed::class,

        Interfaces\MerchantAccount\MerchantAccountInterface::class => Entities\MerchantAccount\MerchantAccount::class,
    ];

    private const TYPES = [
        Interfaces\Payment\PaymentRetrievedInterface::class => [
            'array_key' => 'status',
            PaymentStatus::AUTHORIZATION_REQUIRED => Interfaces\Payment\PaymentAuthorizationRequiredInterface::class,
            PaymentStatus::AUTHORIZING => Interfaces\Payment\PaymentAuthorizingInterface::class,
            PaymentStatus::AUTHORIZED => Interfaces\Payment\PaymentAuthorizedInterface::class,
            PaymentStatus::EXECUTED => Interfaces\Payment\PaymentExecutedInterface::class,
            PaymentStatus::SETTLED => Interfaces\Payment\PaymentSettledInterface::class,
            PaymentStatus::FAILED => Interfaces\Payment\PaymentFailedInterface::class,
        ],
        Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface::class => [
            'array_key' => 'status',
            AuthorizationFlowStatusTypes::AUTHORIZING => Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface::class,
            AuthorizationFlowStatusTypes::FAILED => Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailedInterface::class,
        ],
        Interfaces\Payment\AuthorizationFlow\ActionInterface::class => [
            'array_key' => 'type',
            AuthorizationFlowActionTypes::PROVIDER_SELECTION => Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface::class,
            AuthorizationFlowActionTypes::REDIRECT => Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface::class,
            AuthorizationFlowActionTypes::WAIT => Interfaces\Payment\AuthorizationFlow\Action\WaitActionInterface::class,
        ],
        Interfaces\AccountIdentifier\AccountIdentifierInterface::class => [
            'array_key' => 'type',
            AccountIdentifierTypes::SORT_CODE_ACCOUNT_NUMBER => Interfaces\AccountIdentifier\ScanInterface::class,
            AccountIdentifierTypes::IBAN => Interfaces\AccountIdentifier\IbanInterface::class,
            AccountIdentifierTypes::BBAN => Interfaces\AccountIdentifier\BbanInterface::class,
            AccountIdentifierTypes::NRB => Interfaces\AccountIdentifier\NrbInterface::class,
        ],
        Interfaces\Beneficiary\BeneficiaryInterface::class => [
            'array_key' => 'type',
            BeneficiaryTypes::EXTERNAL_ACCOUNT => Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface::class,
            BeneficiaryTypes::MERCHANT_ACCOUNT => Interfaces\Beneficiary\MerchantBeneficiaryInterface::class,
        ],
        Interfaces\PaymentMethod\PaymentMethodInterface::class => [
            'array_key' => 'type',
            PaymentMethods::BANK_TRANSFER => Interfaces\PaymentMethod\BankTransferPaymentMethodInterface::class,
        ],
        Interfaces\Provider\ProviderSelectionInterface::class => [
            'array_key' => 'type',
            PaymentMethods::PROVIDER_TYPE_USER_SELECTION => Interfaces\Provider\UserSelectedProviderSelectionInterface::class,
        ],
        Interfaces\Payout\PayoutBeneficiaryInterface::class => [
            'array_key' => 'type',
            BeneficiaryTypes::PAYMENT_SOURCE => Interfaces\Payout\PaymentSourceBeneficiaryInterface::class,
            BeneficiaryTypes::EXTERNAL_ACCOUNT => Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface::class,
        ],
        Interfaces\Payout\PayoutRetrievedInterface::class => [
            'array_key' => 'status',
            PayoutStatus::PENDING => Interfaces\Payout\PayoutPendingInterface::class,
            PayoutStatus::AUTHORIZED => Interfaces\Payout\PayoutAuthorizedInterface::class,
            PayoutStatus::EXECUTED => Interfaces\Payout\PayoutExecutedInterface::class,
            PayoutStatus::FAILED => Interfaces\Payout\PayoutFailedInterface::class,
        ],
    ];

    /**
     * @template T of object
     *
     * @param class-string<T> $abstract
     * @param mixed[]|null $data
     *
     * @return T
     * @throws InvalidArgumentException
     *
     * @throws ValidationException
     */
    public function make(string $abstract, array $data = null)
    {
        $abstract = $this->getTypeAbstract($abstract, $data);
        $concrete = self::BINDINGS[$abstract] ?? null;

        if (!$concrete) {
            throw new InvalidArgumentException("Could not find concrete implementation for {$abstract}");
        }

        if (\method_exists($this, $concrete)) {
            return $this->{$concrete}($data);
        }

        // @phpstan-ignore-next-line
        $instance = $this->makeConcrete($concrete);

        if ($data && $instance instanceof Interfaces\HasAttributesInterface) {
            $instance->fill($data);
        }

        // @phpstan-ignore-next-line
        return $instance;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $abstract
     * @param mixed[] $data
     *
     * @return T[]
     * @throws InvalidArgumentException
     *
     * @throws ValidationException
     */
    public function makeMany(string $abstract, array $data): array
    {
        return \array_map(function ($item) use ($abstract) {
            if (!\is_array($item)) {
                throw new InvalidArgumentException('Item is not array');
            }

            return $this->make($abstract, $item);
        }, $data);
    }

    /**
     * @return Interfaces\HppInterface
     * @throws InvalidArgumentException
     *
     */
    private function makeHpp(): Interfaces\HppInterface
    {
        $baseUrl = $this->sdkConfig->shouldUseProduction()
            ? Endpoints::HPP_PROD_URL
            : Endpoints::HPP_SANDBOX_URL;

        return $this->makeConcrete(Hpp::class)->baseUrl($baseUrl);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $concrete
     *
     * @return T
     * @throws InvalidArgumentException
     *
     */
    private function makeConcrete(string $concrete)
    {
        // We could just return the new instances but PHPStan doesn't understand
        // is_subclass_of so we need to rely on the instanceof operator.
        $instance = null;

        if (\is_subclass_of($concrete, Entities\Entity::class)) {
            $instance = new $concrete($this->validatorFactory, $this, $this->apiFactory);
        } elseif (\is_subclass_of($concrete, Entities\EntityBuilder::class)) {
            $instance = new $concrete($this);
        }

        if ($instance instanceof $concrete) {
            return $instance;
        }

        throw new InvalidArgumentException("Provided concrete class {$concrete} must be an Entity or EntityBuilder");
    }

    /**
     * Recursively look in the TYPES array to find an abstract based on the provided data.
     *
     * @param class-string $abstract
     * @param mixed[]|null $data
     *
     * @return class-string
     */
    private function getTypeAbstract(string $abstract, array $data = null): string
    {
        if (isset(self::TYPES[$abstract]) && !empty($data)) {
            $typeConfig = self::TYPES[$abstract];

            // Get the "type" of the model based on the provided array_key
            $key = $typeConfig['array_key'];
            $type = Arr::get($data, $key);

            if (\is_string($type) && isset($typeConfig[$type])) {
                $typeAbstract = $typeConfig[$type];
                if (\interface_exists($typeAbstract) || \class_exists($typeAbstract)) {
                    return $this->getTypeAbstract($typeAbstract, $data);
                }
            }
        }

        return $abstract;
    }
}
