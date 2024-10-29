<?php

declare(strict_types=1);

use TrueLayer\Constants\AccountIdentifierTypes;
use TrueLayer\Constants\AuthorizationFlowActionTypes;
use TrueLayer\Constants\AuthorizationFlowStatusTypes;
use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Constants\PayoutStatus;
use TrueLayer\Constants\RefundStatus;
use TrueLayer\Constants\RemitterVerificationTypes;
use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Constants\WebhookEventTypes;
use TrueLayer\Interfaces;

return [
    Interfaces\Payment\PaymentRetrievedInterface::class => [
        'discriminate_on' => 'status',
        PaymentStatus::AUTHORIZATION_REQUIRED => Interfaces\Payment\PaymentAuthorizationRequiredInterface::class,
        PaymentStatus::AUTHORIZING => Interfaces\Payment\PaymentAuthorizingInterface::class,
        PaymentStatus::AUTHORIZED => Interfaces\Payment\PaymentAuthorizedInterface::class,
        PaymentStatus::EXECUTED => Interfaces\Payment\PaymentExecutedInterface::class,
        PaymentStatus::SETTLED => Interfaces\Payment\PaymentSettledInterface::class,
        PaymentStatus::FAILED => Interfaces\Payment\PaymentFailedInterface::class,
        PaymentStatus::ATTEMPT_FAILED => Interfaces\Payment\PaymentAttemptFailedInterface::class,
    ],
    Interfaces\Payment\AuthorizationFlow\AuthorizationFlowResponseInterface::class => [
        'discriminate_on' => 'status',
        AuthorizationFlowStatusTypes::AUTHORIZING => Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizingInterface::class,
        AuthorizationFlowStatusTypes::FAILED => Interfaces\Payment\AuthorizationFlow\AuthorizationFlowAuthorizationFailedInterface::class,
    ],
    Interfaces\Payment\AuthorizationFlow\ActionInterface::class => [
        'discriminate_on' => 'type',
        AuthorizationFlowActionTypes::PROVIDER_SELECTION => Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface::class,
        AuthorizationFlowActionTypes::REDIRECT => Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface::class,
        AuthorizationFlowActionTypes::WAIT => Interfaces\Payment\AuthorizationFlow\Action\WaitActionInterface::class,
    ],
    Interfaces\AccountIdentifier\AccountIdentifierInterface::class => [
        'discriminate_on' => 'type',
        AccountIdentifierTypes::SORT_CODE_ACCOUNT_NUMBER => Interfaces\AccountIdentifier\ScanInterface::class,
        AccountIdentifierTypes::IBAN => Interfaces\AccountIdentifier\IbanInterface::class,
        AccountIdentifierTypes::BBAN => Interfaces\AccountIdentifier\BbanInterface::class,
        AccountIdentifierTypes::NRB => Interfaces\AccountIdentifier\NrbInterface::class,
    ],
    Interfaces\Beneficiary\BeneficiaryInterface::class => [
        'discriminate_on' => 'type',
        BeneficiaryTypes::EXTERNAL_ACCOUNT => Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface::class,
        BeneficiaryTypes::MERCHANT_ACCOUNT => Interfaces\Beneficiary\MerchantBeneficiaryInterface::class,
    ],
    Interfaces\PaymentMethod\PaymentMethodInterface::class => [
        'discriminate_on' => 'type',
        PaymentMethods::BANK_TRANSFER => Interfaces\PaymentMethod\BankTransferPaymentMethodInterface::class,
    ],
    Interfaces\Provider\ProviderSelectionInterface::class => [
        'discriminate_on' => 'type',
        PaymentMethods::PROVIDER_TYPE_USER_SELECTION => Interfaces\Provider\UserSelectedProviderSelectionInterface::class,
        PaymentMethods::PROVIDER_TYPE_PRESELECTED => Interfaces\Provider\PreselectedProviderSelectionInterface::class,
    ],
    Interfaces\Scheme\SchemeSelectionInterface::class => [
        'discriminate_on' => 'type',
        SchemeSelectionTypes::INSTANT_ONLY => Interfaces\Scheme\InstantOnlySchemeSelectionInterface::class,
        SchemeSelectionTypes::INSTANT_PREFERRED => Interfaces\Scheme\InstantPreferredSchemeSelectionInterface::class,
        SchemeSelectionTypes::USER_SELECTED => Interfaces\Scheme\UserSelectedSchemeSelectionInterface::class,
        SchemeSelectionTypes::PRESELECTED => Interfaces\Scheme\PreselectedSchemeSelectionInterface::class,
    ],
    Interfaces\Payout\PayoutBeneficiaryInterface::class => [
        'discriminate_on' => 'type',
        BeneficiaryTypes::PAYMENT_SOURCE => Interfaces\Payout\PaymentSourceBeneficiaryInterface::class,
        BeneficiaryTypes::EXTERNAL_ACCOUNT => Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface::class,
        BeneficiaryTypes::BUSINESS_ACCOUNT => Interfaces\Payout\BusinessAccountBeneficiaryInterface::class,
    ],
    Interfaces\Payout\PayoutRetrievedInterface::class => [
        'discriminate_on' => 'status',
        PayoutStatus::PENDING => Interfaces\Payout\PayoutPendingInterface::class,
        PayoutStatus::AUTHORIZED => Interfaces\Payout\PayoutAuthorizedInterface::class,
        PayoutStatus::EXECUTED => Interfaces\Payout\PayoutExecutedInterface::class,
        PayoutStatus::FAILED => Interfaces\Payout\PayoutFailedInterface::class,
    ],
    Interfaces\Payment\RefundRetrievedInterface::class => [
        'discriminate_on' => 'status',
        RefundStatus::PENDING => Interfaces\Payment\RefundPendingInterface::class,
        RefundStatus::AUTHORIZED => Interfaces\Payment\RefundAuthorizedInterface::class,
        RefundStatus::EXECUTED => Interfaces\Payment\RefundExecutedInterface::class,
        RefundStatus::FAILED => Interfaces\Payment\RefundFailedInterface::class,
    ],
    Interfaces\Webhook\EventInterface::class => [
        'discriminate_on' => 'type',
        WebhookEventTypes::PAYMENT_AUTHORIZED => Interfaces\Webhook\PaymentAuthorizedEventInterface::class,
        WebhookEventTypes::PAYMENT_EXECUTED => Interfaces\Webhook\PaymentExecutedEventInterface::class,
        WebhookEventTypes::PAYMENT_FAILED => Interfaces\Webhook\PaymentFailedEventInterface::class,
        WebhookEventTypes::PAYMENT_SETTLED => Interfaces\Webhook\PaymentSettledEventInterface::class,
        WebhookEventTypes::PAYMENT_CREDITABLE => Interfaces\Webhook\PaymentCreditableEventInterface::class,
        WebhookEventTypes::PAYMENT_SETTLEMENT_STALLED => Interfaces\Webhook\PaymentSettlementStalledEventInterface::class,
        WebhookEventTypes::REFUND_EXECUTED => Interfaces\Webhook\RefundExecutedEventInterface::class,
        WebhookEventTypes::REFUND_FAILED => Interfaces\Webhook\RefundFailedEventInterface::class,
        WebhookEventTypes::PAYOUT_EXECUTED => Interfaces\Webhook\PayoutExecutedEventInterface::class,
        WebhookEventTypes::PAYOUT_FAILED => Interfaces\Webhook\PayoutFailedEventInterface::class,
    ],
    Interfaces\Webhook\PaymentMethod\PaymentMethodInterface::class => [
        'discriminate_on' => 'type',
        PaymentMethods::BANK_TRANSFER => Interfaces\Webhook\PaymentMethod\BankTransferPaymentMethodInterface::class,
        PaymentMethods::MANDATE => Interfaces\Webhook\PaymentMethod\MandatePaymentMethodInterface::class,
    ],
    Interfaces\Webhook\Beneficiary\BeneficiaryInterface::class => [
        'discriminate_on' => 'type',
        BeneficiaryTypes::PAYMENT_SOURCE => Interfaces\Webhook\Beneficiary\PaymentSourceBeneficiaryInterface::class,
        BeneficiaryTypes::BUSINESS_ACCOUNT => Interfaces\Webhook\Beneficiary\BusinessAccountBeneficiaryInterface::class,
        BeneficiaryTypes::EXTERNAL_ACCOUNT => Interfaces\Webhook\Beneficiary\ExternalAccountBeneficiaryInterface::class,
    ],
    Interfaces\Remitter\RemitterVerification\RemitterVerificationInterface::class => [
        'discriminate_on' => 'type',
        RemitterVerificationTypes::AUTOMATED => Interfaces\Remitter\RemitterVerification\AutomatedRemitterVerificationInterface::class,
    ],
];
