<?php

declare(strict_types=1);

use TrueLayer\Entities;
use TrueLayer\Interfaces;

return [
    Interfaces\HppInterface::class => 'makeHpp',

    Interfaces\AddressInterface::class => Entities\Address::class,
    Interfaces\AddressRetrievedInterface::class => Entities\AddressRetrieved::class,
    Interfaces\UserInterface::class => Entities\User::class,

    Interfaces\Payment\Beneficiary\BeneficiaryBuilderInterface::class => Entities\Payment\Beneficiary\BeneficiaryBuilder::class,
    Interfaces\Payment\Beneficiary\MerchantBeneficiaryInterface::class => Entities\Payment\Beneficiary\MerchantBeneficiary::class,
    Interfaces\Payment\Beneficiary\ExternalAccountBeneficiaryInterface::class => Entities\Payment\Beneficiary\ExternalAccountBeneficiary::class,

    Interfaces\Payment\PaymentRequestInterface::class => Entities\Payment\PaymentRequest::class,
    Interfaces\Payment\PaymentRiskAssessmentInterface::class => Entities\Payment\PaymentRiskAssessment::class,
    Interfaces\Payment\PaymentCreatedInterface::class => Entities\Payment\PaymentCreated::class,
    Interfaces\Payment\PaymentAuthorizationRequiredInterface::class => Entities\Payment\PaymentRetrieved\PaymentAuthorizationRequired::class,
    Interfaces\Payment\PaymentAuthorizingInterface::class => Entities\Payment\PaymentRetrieved\PaymentAuthorizing::class,
    Interfaces\Payment\PaymentAuthorizedInterface::class => Entities\Payment\PaymentRetrieved\PaymentAuthorized::class,
    Interfaces\Payment\PaymentExecutedInterface::class => Entities\Payment\PaymentRetrieved\PaymentExecuted::class,
    Interfaces\Payment\PaymentSettledInterface::class => Entities\Payment\PaymentRetrieved\PaymentSettled::class,
    Interfaces\Payment\PaymentFailedInterface::class => Entities\Payment\PaymentRetrieved\PaymentFailed::class,
    Interfaces\Payment\PaymentAttemptFailedInterface::class => Entities\Payment\PaymentRetrieved\PaymentAttemptFailed::class,
    Interfaces\Payment\PaymentSourceInterface::class => Entities\Payment\PaymentRetrieved\PaymentSource::class,

    Interfaces\Payment\StartAuthorizationFlowRequestInterface::class => Entities\Payment\AuthorizationFlow\StartAuthorizationFlowRequest::class,
    Interfaces\Payment\AuthorizationFlow\AuthorizationFlowInterface::class => Entities\Payment\AuthorizationFlow\AuthorizationFlow::class,
    Interfaces\Payment\AuthorizationFlow\ConfigurationInterface::class => Entities\Payment\AuthorizationFlow\Configuration::class,
    Interfaces\Payment\AuthorizationFlow\ActionInterface::class => Entities\Payment\AuthorizationFlow\Action::class,
    Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface::class => Entities\Payment\AuthorizationFlow\Action\ProviderSelectionAction::class,
    Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface::class => Entities\Payment\AuthorizationFlow\Action\RedirectAction::class,
    Interfaces\Payment\AuthorizationFlow\Action\WaitActionInterface::class => Entities\Payment\AuthorizationFlow\Action\WaitAction::class,
    Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface::class => Entities\Payment\AuthorizationFlow\AuthorizationFlowAuthorizing::class,
    Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailedInterface::class => Entities\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailed::class,

    Interfaces\PaymentMethod\PaymentMethodBuilderInterface::class => Entities\Payment\PaymentMethod\PaymentMethodBuilder::class,
    Interfaces\PaymentMethod\BankTransferPaymentMethodInterface::class => Entities\Payment\PaymentMethod\BankTransferPaymentMethod::class,

    Interfaces\Payment\RefundRequestInterface::class => Entities\Payment\Refund\RefundRequest::class,
    Interfaces\Payment\RefundCreatedInterface::class => Entities\Payment\Refund\RefundCreated::class,
    Interfaces\Payment\RefundPendingInterface::class => Entities\Payment\Refund\RefundPending::class,
    Interfaces\Payment\RefundAuthorizedInterface::class => Entities\Payment\Refund\RefundAuthorized::class,
    Interfaces\Payment\RefundExecutedInterface::class => Entities\Payment\Refund\RefundExecuted::class,
    Interfaces\Payment\RefundFailedInterface::class => Entities\Payment\Refund\RefundFailed::class,

    Interfaces\Provider\ProviderSelectionBuilderInterface::class => Entities\Provider\ProviderSelection\ProviderSelectionBuilder::class,
    Interfaces\Provider\UserSelectedProviderSelectionInterface::class => Entities\Provider\ProviderSelection\UserSelectedProviderSelection::class,
    Interfaces\Provider\PreselectedProviderSelectionInterface::class => Entities\Provider\ProviderSelection\PreselectedProviderSelection::class,
    Interfaces\Provider\ProviderInterface::class => Entities\Provider\Provider::class,
    Interfaces\Provider\ProviderFilterInterface::class => Entities\Provider\ProviderSelection\ProviderFilter::class,

    Interfaces\Payment\Scheme\SchemeSelectionBuilderInterface::class => Entities\Payment\Scheme\SchemeSelectionBuilder::class,
    Interfaces\Payment\Scheme\InstantOnlySchemeSelectionInterface::class => Entities\Payment\Scheme\InstantOnlySchemeSelection::class,
    Interfaces\Payment\Scheme\InstantPreferredSchemeSelectionInterface::class => Entities\Payment\Scheme\InstantPreferredSchemeSelection::class,
    Interfaces\Payment\Scheme\UserSelectedSchemeSelectionInterface::class => Entities\Payment\Scheme\UserSelectedSchemeSelection::class,
    Interfaces\Payment\Scheme\PreselectedSchemeSelectionInterface::class => Entities\Payment\Scheme\PreselectedSchemeSelection::class,

    Interfaces\AccountIdentifier\AccountIdentifierBuilderInterface::class => Entities\AccountIdentifier\AccountIdentifierBuilder::class,
    Interfaces\AccountIdentifier\ScanInterface::class => Entities\AccountIdentifier\Scan::class,
    Interfaces\AccountIdentifier\ScanDetailsInterface::class => Entities\AccountIdentifier\Iban::class,
    Interfaces\AccountIdentifier\IbanInterface::class => Entities\AccountIdentifier\Iban::class,
    Interfaces\AccountIdentifier\IbanDetailsInterface::class => Entities\AccountIdentifier\Iban::class,
    Interfaces\AccountIdentifier\BbanInterface::class => Entities\AccountIdentifier\Bban::class,
    Interfaces\AccountIdentifier\BbanDetailsInterface::class => Entities\AccountIdentifier\Bban::class,
    Interfaces\AccountIdentifier\NrbInterface::class => Entities\AccountIdentifier\Nrb::class,
    Interfaces\AccountIdentifier\NrbDetailsInterface::class => Entities\AccountIdentifier\Nrb::class,

    Interfaces\Payout\Beneficiary\BeneficiaryBuilderInterface::class => Entities\Payout\Beneficiary\BeneficiaryBuilder::class,
    Interfaces\Payout\Beneficiary\BusinessAccountBeneficiaryInterface::class => Entities\Payout\Beneficiary\BusinessAccountBeneficiary::class,
    Interfaces\Payout\Beneficiary\ExternalAccountBeneficiaryInterface::class => Entities\Payout\Beneficiary\ExternalAccountBeneficiary::class,
    Interfaces\Payout\Beneficiary\PaymentSourceBeneficiaryInterface::class => Entities\Payout\Beneficiary\PaymentSourceBeneficiary::class,

    Interfaces\Payout\Scheme\SchemeSelectionBuilderInterface::class => Entities\Payout\Scheme\SchemeSelectionBuilder::class,
    Interfaces\Payout\Scheme\InstantOnlySchemeSelectionInterface::class => Entities\Payout\Scheme\InstantOnlySchemeSelection::class,
    Interfaces\Payout\Scheme\InstantPreferredSchemeSelectionInterface::class => Entities\Payout\Scheme\InstantPreferredSchemeSelection::class,
    Interfaces\Payout\Scheme\PreselectedSchemeSelectionInterface::class => Entities\Payout\Scheme\PreselectedSchemeSelection::class,

    Interfaces\Payout\PayoutCreatedInterface::class => Entities\Payout\PayoutCreated::class,
    Interfaces\Payout\PayoutRequestInterface::class => Entities\Payout\PayoutRequest::class,
    Interfaces\Payout\PayoutPendingInterface::class => Entities\Payout\PayoutRetrieved\PayoutPending::class,
    Interfaces\Payout\PayoutAuthorizedInterface::class => Entities\Payout\PayoutRetrieved\PayoutAuthorized::class,
    Interfaces\Payout\PayoutExecutedInterface::class => Entities\Payout\PayoutRetrieved\PayoutExecuted::class,
    Interfaces\Payout\PayoutFailedInterface::class => Entities\Payout\PayoutRetrieved\PayoutFailed::class,

    Interfaces\Remitter\RemitterInterface::class => Entities\Remitter\Remitter::class,
    Interfaces\Remitter\RemitterVerification\RemitterVerificationBuilderInterface::class => Entities\Remitter\RemitterVerification\RemitterVerificationBuilder::class,
    Interfaces\Remitter\RemitterVerification\AutomatedRemitterVerificationInterface::class => Entities\Remitter\RemitterVerification\AutomatedRemitterVerification::class,

    Interfaces\MerchantAccount\MerchantAccountInterface::class => Entities\MerchantAccount\MerchantAccount::class,

    Interfaces\Webhook\PaymentAuthorizedEventInterface::class => Entities\Webhook\PaymentAuthorizedEvent::class,
    Interfaces\Webhook\PaymentExecutedEventInterface::class => Entities\Webhook\PaymentExecutedEvent::class,
    Interfaces\Webhook\PaymentFailedEventInterface::class => Entities\Webhook\PaymentFailedEvent::class,
    Interfaces\Webhook\PaymentSettledEventInterface::class => Entities\Webhook\PaymentSettledEvent::class,
    Interfaces\Webhook\PaymentCreditableEventInterface::class => Entities\Webhook\PaymentCreditableEvent::class,
    Interfaces\Webhook\PaymentSettlementStalledEventInterface::class => Entities\Webhook\PaymentSettlementStalledEvent::class,
    Interfaces\Webhook\RefundExecutedEventInterface::class => Entities\Webhook\RefundExecutedEvent::class,
    Interfaces\Webhook\RefundFailedEventInterface::class => Entities\Webhook\RefundFailedEvent::class,
    Interfaces\Webhook\PayoutExecutedEventInterface::class => Entities\Webhook\PayoutExecutedEvent::class,
    Interfaces\Webhook\PayoutFailedEventInterface::class => Entities\Webhook\PayoutFailedEvent::class,
    Interfaces\Webhook\PaymentMethod\BankTransferPaymentMethodInterface::class => Entities\Webhook\PaymentMethod\BankTransferPaymentMethod::class,
    Interfaces\Webhook\PaymentMethod\MandatePaymentMethodInterface::class => Entities\Webhook\PaymentMethod\MandatePaymentMethod::class,
    Interfaces\Webhook\Beneficiary\BusinessAccountBeneficiaryInterface::class => Entities\Webhook\Beneficiary\BusinessAccountBeneficiary::class,
    Interfaces\Webhook\Beneficiary\PaymentSourceBeneficiaryInterface::class => Entities\Webhook\Beneficiary\PaymentSourceBeneficiary::class,
    Interfaces\Webhook\Beneficiary\ExternalAccountBeneficiaryInterface::class => Entities\Webhook\Beneficiary\ExternalAccountBeneficiary::class,
    Interfaces\Webhook\Beneficiary\BeneficiaryInterface::class => Entities\Webhook\Beneficiary\Beneficiary::class,

    Interfaces\SignupPlus\SignupPlusBuilderInterface::class => Entities\SignupPlus\SignupPlusBuilder::class,
    Interfaces\SignupPlus\SignupPlusAuthUriRequestInterface::class => Entities\SignupPlus\SignupPlusAuthUriRequest::class,
    Interfaces\SignupPlus\SignupPlusAuthUriCreatedInterface::class => Entities\SignupPlus\SignupPlusAuthUriCreated::class,
    Interfaces\SignupPlus\SignupPlusAccountDetailsInterface::class => Entities\SignupPlus\SignupPlusAccountDetails::class,
    Interfaces\SignupPlus\SignupPlusUserDataRequestInterface::class => Entities\SignupPlus\SignupPlusUserDataRequest::class,
    Interfaces\SignupPlus\SignupPlusUserDataRetrievedInterface::class => Entities\SignupPlus\SignupPlusUserDataRetrieved::class,

    Interfaces\RequestOptionsInterface::class => Entities\RequestOptions::class,
];
