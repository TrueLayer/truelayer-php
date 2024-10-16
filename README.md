## Quick Links

1. [Why use this package?](#why)
2. [Getting started](#getting-started)
3. [Caching](#caching)
4. [Converting to and from arrays](#arrays)
5. [Creating a payment](#creating-a-payment)
    1. [Creating a beneficiary](#creating-a-beneficiary)
    2. [Creating a user](#creating-a-user)
    3. [Creating a payment method](#creating-a-payment-method)
    4. [Creating the payment](#creating-the-payment)
    5. [Creating a payment from an array](#creating-a-payment-from-array)
    6. [Redirecting to the Hosted Payments Page](#redirect-to-hpp)
6. [Retrieving a payment's details](#retrieving-a-payment)
    1. [Get the user](#get-the-user)
    2. [Get the payment method and beneficiary](#get-the-payment-method)
    3. [Check a payment's status](#check-payment-status)
    4. [Working with status specific payment fields](#payment-status-specific-fields)
        1. [Authorization Required Status](#status-authorization-required)
        2. [Authorizing Status](#status-authorizing)
            1. [Provider selection action](#action-provider-selection)
            2. [Redirect action](#action-redirect)
            3. [Wait action](#action-wait)
        3. [Authorized Status](#status-authorized)
        4. [Executed Status](#status-executed)
        5. [Settled Status](#status-settled)
        6. [Failed Status](#status-failed)
        7. [Attempt Failed Status](#status-attempt-failed)
        8. [Authorization flow config](#auth-flow-config)
        9. [Source of funds](#source-of-funds)
7. [Authorizing a payment](#authorizing-payment)
8. [Refunds](#refunds)
9. [Payouts](#payouts)
10. [Merchant accounts](#merchant-accounts)
11. [Account identifiers](#account-identifiers)
12. [Receiving webhook notifications](#webhooks)
13. [Custom idempotency keys](#idempotency)
14. [Custom API calls](#custom-api-calls)
15. [Error Handling](#error-handling)

<a name="why"></a>

## Why use this package?

This package simplifies working with the TrueLayer API, by:

1. Handling authentication (including token expiry) and caching
2. Signing requests
3. Managing idempotency keys, including retrying on conflicts
4. Retrying failed requests, where it makes sense to do so
5. Providing type-hinted methods and classes to work with

<a name="getting-started"></a>

## Getting started

### Installation

The library will require an HTTP client that implements [PSR-18](https://www.php-fig.org/psr/psr-18/).

```
composer require truelayer/client
```

If a PSR-18 client isn't installed, Composer will let you know. You can simply require one, such as Guzzle:

```
composer require guzzlehttp/guzzle truelayer/client
```

### Initialisation

You will need to go to the TrueLayer console and create your credentials which you can then provide to the Client
configurator:

```php
$client = \TrueLayer\Client::configure()
    ->clientId($clientId)
    ->clientSecret($clientSecret)
    ->keyId($kid)
    ->pemFile($pemFilePath) // Or ->pem($contents) Or ->pemBase64($contents)
    ->create();
```

By default, the client library will initialise in `sandbox` mode. To switch to production call `useProduction()`:

```php
$client = \TrueLayer\Client::configure()
    ...
    ->useProduction() // optionally, pass a boolean flag to toggle between production/sandbox mode.
    ->create(); 
```

This library assumes that your client_id is issued with the `payments` scope. Depending on your account type this may
not be the case and the authentication server will return an `invalid_scope` error. You can override the scopes used by
the library with the `scopes()` method:

```php
$client = \TrueLayer\Client::configure()
    ...
    ->scopes('foo', 'bar')
    ->create(); 
```

If needed, you can also provide your own HTTP client instance:

```php
$client = \TrueLayer\Client::configure()
    ...
    ->httpClient($myPSR18Client)
    ->create(); 
```

<a name="caching"></a>

## Caching

The client library supports caching the `client_credentials` grant access token needed to access, create and modify
resources on TrueLayer's systems. In order to enable it, you need to provide an implementation of
the [PSR-16](https://www.php-fig.org/psr/psr-16/) common caching interface and a 32-bytes encryption key.

You can generate a random encryption key by running `openssl rand -hex 32`. This key must be considered secret and
stored next to the client secrets obtained from TrueLayer's console.

```php
$client = \TrueLayer\Client::configure()
    ...
    ->cache($cacheImplementation, $encryptionKey)
    ->create();
```

A good example of a caching library that implements PSR-16 is [illuminate/cache](https://github.com/illuminate/cache).

<a name="arrays"></a>

## Converting to and from arrays

If you want to skip calling each setter method, you can use arrays to create any resource:

```php
$client->beneficiary()->fill($beneficiaryData);
$client->user()->fill($userData);
$client->payment()->fill($paymentData);
// etc...
```

You can also convert any resource to array. This can be convenient if you need to output it to json for example:

```php
$paymentData = $client->getPayment($paymentId)->toArray(); 
```

<a name="creating-a-payment"></a>

## Creating a payment

<a name="creating-a-beneficiary"></a>

### 1. Creating a beneficiary

*Merchant account beneficiary*

```php
// If the merchant account id is known:
$beneficiary = $client->beneficiary()->merchantAccount()
    ->merchantAccountId('a2dcee6d-7a00-414d-a1e6-8a2b23169e00');

// Alternatively you can retrieve merchant accounts and use one of them directly:
$merchantAccounts = $client->getMerchantAccounts();

// Select the merchant account you need...
$merchantAccount = $merchantAccounts[0];

$beneficiary = $client->beneficiary()->merchantAccount($merchantAccount);
```

If your merchant account is configured for payment verification then you have the option to enable automated remitter
verification for your Merchant Account payment:

```php
$remitterVerification = $client
    ->remitterVerification()
    ->automated()
    ->remitterName(true)
    ->remitterDateOfBirth(true);

$beneficiary = $client->beneficiary()
    ->merchantAccount()
    ->merchantAccountId('a2dcee6d-7a00-414d-a1e6-8a2b23169e00')
    ->verification($remitterVerification);
```

*External account beneficiary - Sort code & account number*

```php
$beneficiary = $client->beneficiary()->externalAccount()
    ->reference('Transaction reference')
    ->accountHolderName('John Doe')
    ->accountIdentifier(
        $client->accountIdentifier()->sortCodeAccountNumber()
            ->sortCode('010203')
            ->accountNumber('12345678')
    );
```

*External account beneficiary - IBAN*

```php
$beneficiary = $client->beneficiary()->externalAccount()
    ->reference('Transaction reference')
    ->accountHolderName('John Doe')
    ->accountIdentifier(
        $client->accountIdentifier()->iban()
            ->iban('GB53CLRB04066200002723')
    );
```

<a name="creating-a-user"></a>

### 2. Creating a user

```php
use TrueLayer\Constants\UserPoliticalExposures;

$user = $client->user()
    ->name('Jane Doe')
    ->phone('+44123456789')
    ->email('jane.doe@truelayer.com')
    ->dateOfBirth('2024-01-01');

// You can also set the user's political exposure field if you need to
$user->politicalExposure(UserPoliticalExposures::CURRENT);
```

You are also able to set the user's address:

```php
$address = $client->user()
    ->address()
    ->addressLine1('The Gilbert')
    ->addressLine2('City of')
    ->city('London')
    ->state('London')
    ->zip('EC2A 1PX')
    ->countryCode('GB');
```

<a name="creating-a-payment-method"></a>

### 3. Creating a payment method

You can create a bank transfer payment method with minimal configuration:

```php
$paymentMethod = $client->paymentMethod()->bankTransfer()
    ->beneficiary($beneficiary);
```

Optionally, you can filter the providers that will be returned in the authorisation flow:

```php
use TrueLayer\Constants\Countries;
use TrueLayer\Constants\CustomerSegments;
use TrueLayer\Constants\ReleaseChannels;

// You can filter the providers that will be returned:
$filter = $client->providerFilter()
    ->countries([Countries::GB, Countries::ES])
    ->customerSegments([CustomerSegments::RETAIL, CustomerSegments::CORPORATE])
    ->releaseChannel(ReleaseChannels::PRIVATE_BETA)
    ->excludesProviderIds(['provider-id'])

// You can also filter providers by the schemes they support:
$schemeSelection = $client->schemeSelection()->userSelected(); // Let the user select. You must provide your own UI for this.
$schemeSelection = $client->schemeSelection()->instantOnly(); // Only allow providers that support instant payments
$schemeSelection = $client->schemeSelection()->instantPreferred(); // Prefer providers that allow instant payments, but allow defaulting back to non-instant payments if unavailable.

// For instant only and instant preferred, you can also allow or disallow remitter fees:
$schemeSelection->allowRemitterFee(true); // Unless explicitly set, this will default to false.

// Create the provider selection configuration
$providerSelection = $client->providerSelection()->userSelected()
    ->filter($filter)
    ->schemeSelection($schemeSelection);

// Create the payment method
$paymentMethod = $client->paymentMethod()->bankTransfer()
    ->providerSelection($providerSelection);
```

Alternatively, you can preselect the provider that is going to be used in the authorisation flow as well as the payment
scheme that the payment is going to be sent on:

```php
// Preselect the payment scheme
$schemeSelection = $client->schemeSelection()
    ->preselected()
    ->schemeId('faster_payments_service');

// Preselect the provider
$providerSelection = $client->providerSelection()
    ->preselected()
    ->providerId('mock-payments-gb-redirect')
    ->schemeSelection($schemeSelection);

// Create the payment method
$paymentMethod = $client->paymentMethod()->bankTransfer()
    ->providerSelection($providerSelection);
```

You can also enable payment retries, but make sure you can handle the `attempt_failed` payment status beforehand:

```php
$paymentMethod = $client->paymentMethod()->bankTransfer()
    ->enablePaymentRetry()
    ->beneficiary($beneficiary);
```

<a name="creating-the-payment"></a>

### 4. Creating the payment

```php
$payment = $client->payment()
    ->user($user)
    ->amountInMinor(1)
    ->currency(\TrueLayer\Constants\Currencies::GBP) // You can use other currencies defined in this class.
    ->metadata([ // add custom key value pairs
        'key' => 'value'
    ])
    ->paymentMethod($paymentMethod)
    ->create();
```

You then get access to the following methods:

```php
$payment->getId(); // The payment id
$payment->getResourceToken(); // The resource token 
$payment->getDetails(); // Get the payment details, same as $client->getPayment($paymentId)
$payment->hostedPaymentsPage(); // Get the Hosted Payments Page helper, see below.
$payment->toArray(); // Convert to array
```

<a name="creating-a-payment-from-array"></a>

### 5. Creating a payment from an array

If you prefer, you can work directly with arrays by calling the `fill` method:

```php
$paymentData = [
    'amount_in_minor' => 1,
    'currency' => Currencies::GBP,
    'payment_method' => [
        'type' => PaymentMethods::BANK_TRANSFER,
        'beneficiary' => [
            'account_identifier' => [
                'account_number' => '12345678',
                'sort_code' => '010203',
                'type' => 'sort_code_account_number',
            ],
            'reference' => 'Transaction reference',
            'account_holder_name' => 'John Doe',
            'type' => 'external_account',
        ],
        'provider_selection' => [
            'type' => PaymentMethods::PROVIDER_TYPE_USER_SELECTION,
            'filter' => [
                'countries' => [
                    Countries::GB,
                ],
                'release_channel' => ReleaseChannels::PRIVATE_BETA,
                'customer_segments' => [
                    CustomerSegments::RETAIL,
                ],
                'provider_ids' => [
                    'mock-payments-gb-redirect',
                ],
                'excludes' => [
                    'provider_ids' => [],
                ],
            ],
        ],
    ],
];

$payment = $client->payment()->fill($paymentData)->create();
```

<a name="redirect-to-hpp"></a>

### 6. Redirecting to the Hosted Payments Page

TrueLayer's Hosted Payment Page provides a high-converting UI for payment authorization that supports, out of the box,
all action types. You can easily get the URL to redirect to after creating your payment:

```php
$url = $client->payment()
    ...
    ->create()
    ->hostedPaymentsPage()
    ->returnUri('http://www.mymerchantwebsite.com')
    ->primaryColour('#000000')
    ->secondaryColour('#e53935')
    ->tertiaryColour('#32329f')
    ->toUrl();
```

<a name="retrieving-a-payment"></a>

# Retrieving a payment's details

```php
$payment = $client->getPayment($paymentId);
$payment->getId();
$payment->getUserId();
$payment->getAmountInMinor();
$payment->getCreatedAt(); 
$payment->getCurrency();
$payment->getPaymentMethod();
$payment->getMetadata();
$payment->toArray();
```

<a name="get-the-payment-method"></a>

## Get the payment method and beneficiary

```php
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\MerchantBeneficiaryInterface;

$method = $client->getPayment($paymentId)->getPaymentMethod();

if ($method instanceof BankTransferPaymentMethodInterface) {
    $providerSelection = $method->getProviderSelection();
    $beneficiary = $method->getBeneficiary();
    $beneficiary->getAccountHolderName();
    
    if ($beneficiary instanceof ExternalAccountBeneficiaryInterface) {
        $beneficiary->getReference();
        $beneficiary->getAccountIdentifier(); // See account identifiers documentation
    }
    
    if ($beneficiary instanceof MerchantBeneficiaryInterface) {
        $beneficiary->getReference();
        $beneficiary->getMerchantAccountId();
    }
}
```

<a name="check-payment-status"></a>

## Check a payment's status

You can check for the status by using one of the following helper methods:

```php
$payment = $client->getPayment($paymentId);
$payment->isAuthorizationRequired();
$payment->isAuthorizing();
$payment->isAuthorized(); // Will also return false when the payment has progressed to executed, failed or settled states.
$payment->isExecuted(); // Will also return false when the payment has progressed to failed or settled states.
$payment->isSettled(); 
$payment->isFailed(); // Payment has failed
$payment->isAttemptFailed(); // Payment attempt has failed, only available if payment retries are enabled.
```

Or you can get the status as a string and compare it to the provided constants in `PaymentStatus`:

```php
$payment = $client->getPayment($paymentId);
$payment->getStatus() === \TrueLayer\Constants\PaymentStatus::AUTHORIZATION_REQUIRED;
```

<a name="payment-status-specific-fields"></a>

## Working with status specific payment fields

<a name="status-authorization-required"></a>

### Authorization Required Status

> Payment with this status is on its initial phase where no action beyond the creation of the payment was taken.

```php
use TrueLayer\Interfaces\Payment\PaymentAuthorizationRequiredInterface;

if ($payment instanceof PaymentAuthorizationRequiredInterface) {
    // Your logic here, you would normally start the authorization process.
}
```

<a name="status-authorizing"></a>

### Authorizing Status

> Payment has its authorization_flow started, but the authorization has not completed yet

A payment in `Authorizing` will expose 2 additional methods for retrieving:

- the [authorization flow config](#auth-flow-config)
- the next action in the payment authorization user journey

```php
use TrueLayer\Interfaces\Payment\PaymentAuthorizingInterface;

if ($payment instanceof PaymentAuthorizingInterface) {
    $payment->getAuthorizationFlowConfig(); // see authorization flow config
    
    // Will return a \TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ActionInterface
    $payment->getAuthorizationFlowNextAction(); 
}
```

<a name="action-provider-selection"></a>

#### Provider selection action

This action indicates that the user needs to select a provider from the provided list. To render the provider list, each
provider comes with helpful methods for retrieving the name, logo, id, etc.

```php
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;

$nextAction = $payment->getAuthorizationFlowNextAction();

if ($nextAction instanceof ProviderSelectionActionInterface) {
    foreach ($nextAction->getProviders() as $provider) {
        $provider->getId();
        $provider->getDisplayName();
        $provider->getCountryCode();
        $provider->getLogoUri();
        $provider->getIconUri();
        $provider->getBgColor();       
    }
}

```

<a name="action-redirect"></a>

#### Redirect action

This action indicates that the user needs to be redirected to complete the authorization process.

```php
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\RedirectActionInterface;

$nextAction = $payment->getAuthorizationFlowNextAction();

if ($nextAction instanceof RedirectActionInterface) {
    $nextAction->getUri(); // The URL the end user must be redirected to
    $nextAction->getProvider(); // The provider object, see available methods above.
}
```

<a name="action-wait"></a>

#### Wait action

```php
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\WaitActionInterface;

$nextAction = $payment->getAuthorizationFlowNextAction();

if ($nextAction instanceof WaitActionInterface) {
    // your logic here   
}
```

<a name="status-authorized"></a>

### Authorized Status

> Payment has successfully completed its authorization flow

```php
use TrueLayer\Interfaces\Payment\PaymentAuthorizedInterface;

if ($payment instanceof PaymentAuthorizedInterface) {
    $payment->getAuthorizationFlowConfig(); // see authorization flow config
}
```

<a name="status-executed"></a>

### Executed Status

> Payment has been accepted by the bank

```php
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;

if ($payment instanceof PaymentExecutedInterface) {
    $payment->getExecutedAt(); // The date and time the payment was executed at
    $payment->getAuthorizationFlowConfig(); // See authorization flow config
}
```

<a name="status-settled"></a>

### Settled Status

> Payment can transition into this state if the beneficiary account was a merchant account within Truelayer, and Truelayer has observed the money to be settled.

```php
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;

if ($payment instanceof PaymentSettledInterface) {
    $payment->getExecutedAt(); // The date and time the payment was executed at
    $payment->getSettledAt(); // The date and time the payment was settled at
    $payment->getAuthorizationFlowConfig(); // See authorization flow config
    $payment->getSourceOfFunds(); // See source of funds
}
```

<a name="status-failed"></a>

### Failed Status

> Payment has failed. The reason for failure can be observed in failure_reason field on the payment resource

```php
use TrueLayer\Interfaces\Payment\PaymentFailedInterface;

if ($payment instanceof PaymentFailedInterface) {
    $payment->getFailedAt(); // The date and time the payment failed at
    $payment->getFailureStage(); // The status the payment was when it failed, one of `authorization_required`, `authorizing` or `authorized`
    $payment->getFailureReason(); // The reason the payment failed. Handle unexpected values gracefully as an unknown failure.
    $payment->getAuthorizationFlowConfig(); // see authorization flow config
}
```

<a name="status-attempt-failed"></a>

### Attempt Failed Status

> Status only available when you enable payment retries.

```php
use TrueLayer\Interfaces\Payment\PaymentAttemptFailedInterface;

if ($payment instanceof PaymentAttemptFailedInterface) {
    $payment->getFailedAt(); // The date and time the payment failed at
    $payment->getFailureStage(); // The status the payment was when it failed, one of `authorization_required`, `authorizing` or `authorized`
    $payment->getFailureReason(); // The reason the payment failed. Handle unexpected values gracefully as an unknown failure.
    $payment->getAuthorizationFlowConfig(); // see authorization flow config
}
```

<a name="auth-flow-config"></a>

### Authorization flow config

This object provides information about the authorization flow the payment went through.

```php
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;

if ($payment instanceof PaymentExecutedInterface) {
    $config = $payment->getAuthorizationFlowConfig(); 
    $config->isRedirectSupported() // Is redirect supported or not
    $config->getRedirectReturnUri(); // The URL the user will be redirected back once the flow on the third-party's website is completed
    $config->isProviderSelectionSupported(); // Is provider selection supported or not
}
```

<a name="source-of-funds"></a>

### Source of funds

```php
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\SchemeIdentifier\ScanDetailsInterface;
use TrueLayer\Interfaces\SchemeIdentifier\IbanDetailsInterface;
use TrueLayer\Interfaces\SchemeIdentifier\BbanDetailsInterface;
use TrueLayer\Interfaces\SchemeIdentifier\NrbDetailsInterface;

if ($payment instanceof PaymentExecutedInterface || $payment instanceof PaymentSettledInterface) {
    $paymentSource = $payment->getPaymentSource();
    $paymentSource->getAccountHolderName(); // The unique ID for the external account
    $paymentSource->getId(); 
    $paymentSource->toArray();
        
    foreach ($paymentSource->getAccountIdentifiers() as $accountIdentifier) {
       // See 'Account identifiers' for available methods.
    }
}
```

<a name="authorizing-payment"></a>

# Authorizing a payment

## Using the Hosted Payments Page

You are encouraged to use our [HPP](https://docs.truelayer.com/docs/hosted-payment-page) which collects all payment
information required from your users and guides them through the payment authorisation journey. To do this simply
redirect to the HPP after creating a payment. See [Redirecting to HPP](#redirect-to-hpp) to get started.

## Manually starting the authorization flow

In some cases you may want to start the authorization flow manually (for example if you want to render your own provider
selection screen).

This library has incomplete support for the authorization flow. To complete the authorization flow, you will need to
eventually redirect the user to the HPP or implement missing features using direct API calls (
see [Custom API calls](#custom-api-calls)).

```php
use TrueLayer\Constants\FormInputTypes;

$payment = $client->payment()->create();

// If you are planning to start the authorization flow manually then hand over to the HPP:
$payment->authorizationFlow()
    ->returnUri($myReturnUri)
    ->useHPPCapabilities()
    ->start();

// If you are planning to build a fully custom UI, you need to manually specify which features your UI is able to support:
$payment->authorizationFlow()
    ->returnUri($myReturnUri)
    ->enableProviderSelection() // Can the UI render a provider selection screen?
    ->enableSchemeSelection() // Can the UI render a scheme selection screen?
    ->enableUserAccountSelection() // Can the UI render a user account selection screen?
    ->formInputTypes([FormInputTypes::TEXT, FormInputTypes::TEXT_WITH_IMAGE, FormInputTypes::SELECT]) // Can the UI render form inputs for the end user to interact with? Which input types can it handle?
    ->start();
```

Once the authorization flow has been started, refer to [Authorizing payments](#status-authorizing) to understand how to
handle the returned actions.

### Submitting a provider

If your payment requires selecting a provider as its next action, you can render the provider list and then submit the
user selection using the `submitProvider` method:

```php
$client->submitPaymentProvider($payment, $provider);
```

<a name="refunds"></a>

# Refunds

Refunds are only supported for settled merchant account payments.

## Creating and retrieving refunds from the client

```php
use TrueLayer\Interfaces\Payment\RefundRetrievedInterface;
use TrueLayer\Interfaces\Payment\RefundExecutedInterface;
use TrueLayer\Interfaces\Payment\RefundFailedInterface;

// Create and get the refund id
$refundId = $client->refund()
    ->payment($paymentId) // Payment ID, PaymentRetrievedInterface or PaymentCreatedInterface
    ->amountInMinor(1)
    ->reference('My reference')
    ->create()
    ->getId();
    
// Get a refund's details
$refund = $client->getRefund($paymentId, $refundId);

// Common refund methods
$refund->getId();
$refund->getAmountInMinor();
$refund->getCurrency();
$refund->getReference();
$refund->getStatus();
$refund->getCreatedAt();
$refund->isPending();
$refund->isAuthorized();
$refund->isExecuted();
$refund->isFailed();

// Executed refunds
if ($refund instanceof RefundExecutedInterface) {
    $refund->getExecutedAt();
}

// Failed refunds
if ($refund instanceof RefundFailedInterface) {
    $refund->getFailureReason();
    $refund->getFailedAt();
}

// Get all refunds for a payment
$refunds = $client->getRefunds($paymentId); // RefundRetrievedInterface[]
```

## Creating and retrieving refunds from a settled payment

Alternatively, if you already have a payment instance you can use the following convenience methods:

```php
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;

if ($payment instanceof PaymentSettledInterface) {
    // Create a refund
    $refundId = $payment->refund()
        ->amountInMinor(1)
        ->reference('My reference')
        ->create()
        ->getId();
        
    // Get a refund's details
    $payment->getRefund($refundId)
    
    // Get all refunds
    $payment->getRefunds();
}
```

<a name="payouts"></a>

# Payouts

## Creating a payout to an external beneficiary

```php
$accountIdentifier = $client->accountIdentifier()
    ->iban()
    ->iban('GB29NWBK60161331926819');

$beneficiary = $client->payoutBeneficiary()->externalAccount()
    ->accountHolderName('John Doe')
    ->reference('My reference')
    ->accountIdentifier($accountIdentifier);

$payout = $client->payout()
    ->amountInMinor(1)
    ->beneficiary($beneficiary)
    ->currency(\TrueLayer\Constants\Currencies::GBP)
    ->merchantAccountId($merchantAccount->getId())
    ->create();

$payout->getId();
```

## Creating a payout to a payment source (refunds)

```php
$beneficiary = $client->payoutBeneficiary()->paymentSource()
    ->paymentSourceId($paymentSourceId)
    ->reference('My reference')
    ->userId($user->getId());

$payout = $client->payout()
    ->amountInMinor(1)
    ->beneficiary($beneficiary)
    ->currency(\TrueLayer\Constants\Currencies::GBP)
    ->merchantAccountId($merchantAccount->getId())
    ->create();

$payout->getId();
```

## Creating a payout to a preselected business account

```php
$beneficiary = $client->payoutBeneficiary()
    ->businessAccount()
    ->reference('My reference');

$payout = $client->payout()
    ->amountInMinor(1)
    ->beneficiary($beneficiary)
    ->currency(\TrueLayer\Constants\Currencies::GBP)
    ->merchantAccountId($merchantAccount->getId())
    ->create();

$payout->getId();
```

## Retrieving a payout

```php
use TrueLayer\Interfaces\Payout\PayoutRetrievedInterface;
use TrueLayer\Interfaces\Payout\PayoutPendingInterface;
use TrueLayer\Interfaces\Payout\PayoutAuthorizedInterface;
use TrueLayer\Interfaces\Payout\PayoutExecutedInterface;
use TrueLayer\Interfaces\Payout\PayoutFailedInterface;
use TrueLayer\Constants\PayoutStatus;

$payout = $client->getPayout($payoutId);

// All payout statuses implement this common interface
if ($payout instanceof PayoutRetrievedInterface) {
    $payout->getId();
    $payout->getCurrency();
    $payout->getAmountInMinor();
    $payout->getMerchantAccountId();
    $payout->getStatus(); 
    $payout->getBeneficiary();
    $payout->getCreatedAt();
}

// Pending payouts
if ($payout instanceof PayoutPendingInterface) {
    $payout->getStatus(); //PayoutStatus::PENDING
}

// Authorized payouts
if ($payout instanceof PayoutAuthorizedInterface) {
    $payout->getStatus(); //PayoutStatus::AUTHORIZED
}

// Executed payouts
if ($payout instanceof PayoutExecutedInterface) {
    $payout->getStatus(); //PayoutStatus::EXECUTED
    $payout->getExecutedAt();
}

// Failed payouts
if ($payout instanceof PayoutFailedInterface) {
    $payout->getStatus() // PayoutStatus::FAILED
    $payout->getFailedAt();
    $payout->getFailureReason();
}
```

<a name="merchant-accounts"></a>

# Merchant accounts

Listing all merchant accounts:

```php
$merchantAccounts = $client->getMerchantAccounts(); // MerchantAccountInterface[]
```

Retrieving an account by id:

```php
$merchantAccount = $client->getMerchantAccount('a2dcee6d-7a00-414d-a1e6-8a2b23169e00');

$merchantAccount->getAccountHolderName();
$merchantAccount->getAvailableBalanceInMinor();
$merchantAccount->getCurrentBalanceInMinor();
$merchantAccount->getCurrency();
$merchantAccount->getId();

foreach ($merchantAccount->getAccountIdentifiers() as $accountIdentifier) {
    // See 'Account identifiers' for available methods.
}
```

<a name="webhooks"></a>

# Receiving webhook notifications

You can register to receive notifications about your payment or mandate statuses via webhooks. The URI endpoint for the
webhook can be [configured in the Console](https://docs.truelayer.com/docs/set-up-truelayer-console-for-payments-v3)

> ⚠️ All incoming webhook requests must have their signatures verified, otherwise you run the risk of accepting fraudulent payment status events.

This library makes handling webhook events easy and secure. You do not need to manually verify the incoming request
signature as it is done for you. You should add the code below to your webhook endpoint; Alternatively the webhook
service can be configured in your IoC container and in your endpoint you can simply call `$webhook->execute()`.

## Getting a webhook instance

If you already have access to a client instance, it's as easy as:

```php
$webhook = $client->webhook();
```

Alternatively, you can also create an instance from scratch:

```php
$webhook = \TrueLayer\Webhook::configure()
    ->httpClient($httpClient)
    ->cache($cacheImplementation, $encryptionKey)  // optional, but recommeded. See Caching
    ->useProduction($useProduction) // bool
    ->create();
```

## Handling events

You handle events by registering handlers (closures or invokable classes) for the event types you care about. You can
have as many handlers as you wish, however please note the order of execution is not guaranteed.

Your handlers will only execute after the request signature is verified, and the incoming webhook type is matched to the
interface you typehinted in your handler.

[Jump to supported event types](#webhook-types)

### Closure handlers

```php
use TrueLayer\Interfaces\Webhook;

$client->webhook()
    ->handler(function (Webhook\EventInterface $event) {
        // Do something on any event
    })
    ->handler(function (Webhook\PaymentEventInterface $event) {
        // Do something on any payment event
    })
    ->handler(function (Webhook\PaymentExecutedEventInterface $event) {
        // Do something on payment executed event only
    })
    ->execute();
```

### Invokable classes

```php
use TrueLayer\Interfaces\Webhook;

class LogEvents
{
    public function __invoke(Webhook\EventInterface $event)
    {
        // Log event
    }
}

class UpdateOrderStatus
{
    public function __invoke(Webhook\PaymentExecutedEventInterface $event)
    {
        // Update your order when the payment is executed
    }
}

// You can use ->handler()...
$client->webhook()
    ->handler(LogEvents::class)
    ->handler(UpdateOrderStatus::class)
    ->execute();

// Or you can use ->handlers()...
$client->webhook()
    ->handlers(
        LogEvents::class,
        UpdateOrderStatus::class
    )
    ->execute();

// If you need to, you can also provide instances:
$client->webhook()
    ->handlers(
        new LogEvents(),
        new UpdateOrderStatus()
    )
    ->execute();
```

<a name="webhook-types"></a>

## Supported handler types

This library supports handlers for the following event types:

- payment_authorized
- payment_executed
- payment_settled
- payment_failed
- payment_creditable
- payment_settlement_stalled
- refund_executed
- refund_failed
- payout_executed
- payout_failed

You can also handle other event types by typehinting `TrueLayer\Interfaces\Webhook\EventInterface`
in your handler. You can then get the payload data by calling the `getBody()` method on your variable.

All events inherit from `EventInterface`.

```php

use TrueLayer\Interfaces\Webhook;

$client->webhook()
    ->handler(function (Webhook\EventInterface $event) {
        // Handle any incoming event
        $event->getEventId();
        $event->getEventVersion();
        $event->getSignature();
        $event->getTimestamp();
        $event->getType();
        $event->getBody();
    })
    ->handler(function (Webhook\PaymentEventInterface $event) {
        // Handle any payment event
        // Inherits from EventInterface so provides same methods plus:
        $event->getPaymentId();
        $event->getMetadata();        
    })
    ->handler(function (Webhook\PaymentAuthorizedEventInterface $event) {
        // Handle payment authorized
        // Note that this webhook is optional and disabled by default.
        // Contact us if you would like this webhook to be enabled.
        // Inherits from PaymentEventInterface so provides same methods plus:
        $event->getAuthorizedAt();
        $event->getPaymentMethod();
        $event->getPaymentSource();
    })
    ->handler(function (Webhook\PaymentExecutedEventInterface $event) {
        // Handle payment executed
        // Inherits from PaymentEventInterface so provides same methods plus:
        $event->getExecutedAt();
        $event->getSettlementRiskCategory();
        $event->getPaymentMethod();
        $event->getPaymentSource();
    })
    ->handler(function (Webhook\PaymentSettledEventInterface $event) {
        // Handle payment settled
        // Inherits from PaymentEventInterface so provides same methods plus:
        $event->getSettledAt();
        $event->getSettlementRiskCategory();
        $event->getPaymentMethod();
        $event->getPaymentSource();
    })
    ->handler(function (Webhook\PaymentFailedEventInterface $event) {
        // Handle payment failed
        // Inherits from PaymentEventInterface so provides same methods plus:
        $event->getFailedAt();
        $event->getFailureReason();
        $event->getFailureStage();
        $event->getPaymentMethod();
        $event->getPaymentSource();
    })
    ->handler(function (Webhook\PaymentCreditableEventInterface $event) {
        // Handle payment creditable
        // Inherits from PaymentEventInterface so provides same methods plus:
        $event->getCreditableAt();
    })
    ->handler(function (Webhook\PaymentSettlementStalledEventInterface $event) {
        // Handle payment settlement stalled
        // Note that this webhook is optional and disabled by default.
        // Contact us if you would like this webhook to be enabled.
        // Inherits from PaymentEventInterface so provides same methods plus:
        $event->getSettlementStalledAt();
    })
    ->handler(function (Webhook\RefundEventInterface $event) {
        // Handle any refund event
        $event->getPaymentId();
        $event->getRefundId();
    })
    ->handler(function (Webhook\RefundExecutedEventInterface $event) {
        // Handle refund executed
        // Inherits from RefundEventInterface so provides same methods plus:
        $event->getExecutedAt();
        $event->getSchemeId();
    })
    ->handler(function (Webhook\RefundFailedEventInterface $event) {
        // Handle refund failed
        // Inherits from RefundEventInterface so provides same methods plus:
        $event->getFailedAt();
        $event->getFailureReason();
    })
    ->handler(function (Webhook\PayoutEventInterface $event) {
        // handle any payout event
        $event->getPayoutId();
        $beneficiary = $event->getBeneficiary();
        $beneficiary->getType();
        
        if ($beneficiary instanceof Webhook\Beneficiary\BusinessAccountBeneficiaryInterface) {
            $beneficiary->getType();
        }
        
        if ($beneficiary instanceof Webhook\Beneficiary\PaymentSourceBeneficiaryInterface) {
            $beneficiary->getPaymentSourceId();
            $beneficiary->getUserId();
        }
    })
    ->handler(function (Webhook\PayoutExecutedEventInterface $event) {
        // handle payout executed
        // Inherits from PayoutEventInterface so provides same methods plus:
        $event->getExecutedAt();
    })
    ->handler(function (Webhook\PayoutFailedEventInterface $event) {
        // handle payout failed
        // Inherits from PayoutEventInterface so provides same methods plus:
        $event->getFailedAt();
        $event->getFailureReason();
    })
    ->execute();
```

## Payment source

PaymentAuthorizedEventInterface, PaymentExecutedEventInterface, PaymentSettledEventInterface,
PaymentFailedEventInterface provide a method to get more information about the payment source:

```php
$paymentSource = $event->getPaymentSource(); $paymentSource->getId(); $paymentSource->getAccountHolderName();
$paymentSource->getAccountIdentifiers(); // See Account Identifiers
```

### Payment method

PaymentAuthorizedEventInterface, PaymentExecutedEventInterface, PaymentSettledEventInterface,
PaymentFailedEventInterface provide a method to get more information about the payment method:

```php
use TrueLayer\Interfaces\Webhook;

$paymentMethod = $event->getPaymentMethod(); 
$paymentMethod->getType();

if ($paymentMethod instanceof Webhook\PaymentMethod\BankTransferPaymentMethodInterface) {
    $paymentMethod->getProviderId();
    $paymentMethod->getSchemeId();
}      

if ($paymentMethod instanceof Webhook\PaymentMethod\MandatePaymentMethodInterface) {
    $paymentMethod->getMandateId();
    $paymentMethod->getReference();
}
```

## Overriding globals

By default the webhook service will use php globals to read the endpoint path and request headers and body. This
behaviour can be overriden if necessary (for example you may be calling `execute()` in a queued job.):

```php
    $client->webhook()
        ->handlers(...)
        ->path('/my/custom/path')
        ->headers($headers) // flat key-value array
        ->body($body) // the raw request body string
        ->execute();
```

## Signature verification failure

If the webhook signature cannot be verified, a \TrueLayer\Exceptions\WebhookVerificationFailedException will be thrown.
A number of other exceptions will be thrown when the webhook service is misconfigured, please
see [error handling](#error-handling)

<a name="account-identifiers"></a>

# Account identifiers

All account identifiers implement a common interface, so you can access:

```php
$accountIdentifier->getType();
$accountIdentifier->toArray();
```

Based on the specific type, you can get more information:

```php
use TrueLayer\Interfaces\AccountIdentifier\ScanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\IbanDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\NrbDetailsInterface;
use TrueLayer\Interfaces\AccountIdentifier\BbanDetailsInterface;

if ($accountIdentifier instanceof ScanDetailsInterface) {
    $accountIdentifier->getAccountNumber();
    $accountIdentifier->getSortCode();
}

if ($accountIdentifier instanceof IbanDetailsInterface) {
    $accountIdentifier->getIban();
}

if ($accountIdentifier instanceof NrbDetailsInterface) {
    $accountIdentifier->getNrb();
}

if ($accountIdentifier instanceof BbanDetailsInterface) {
    $accountIdentifier->getBban();
}
```

<a name="idempotency"></a>

# Custom idempotency keys

By default, the client will generate and manage idempotency keys for you. However, there are cases when you might want
to set your own idempotency keys and you can do this by using the `requestOptions` setter when creating a resource.

```php
// Create a RequestOptionsInterface instance and set your custom idempotency key
$requestOptions = $client->requestOptions()->idempotencyKey('my-custom-idempotency-key');

// Creating a payment with a custom idempotency key
$client->payment()
    ->paymentMethod($method)
    ->amountInMinor(10)
    ->currency('GBP')
    ->user($user)
    ->requestOptions($requestOptions) 
    ->create();

// Creating a refund with a custom idempotency key
$client->refund()
    ->payment($paymentId)
    ->amountInMinor(1)
    ->reference('My reference')
    ->requestOptions($requestOptions) 
    ->create();

// Creating a payout with a custom idempotency key
$client->payout()
    ->amountInMinor(1)
    ->currency(Currencies::GBP)
    ->merchantAccountId($accountId)
    ->beneficiary($payoutBeneficiary)
    ->requestOptions($requestOptions) 
    ->create();
```

<a name="custom-api-calls"></a>

# Custom API calls

You can use the client library to make your own API calls without worrying about authentication or request signing:

```php
$responseData = $client->getApiClient()->request()->uri('/merchant-accounts')->get();

$responseData = $client->getApiClient()->request()
    ->uri('/payments')
    ->payload($myData)
    ->header('My Header', 'value')
    ->post();
```

<a name="error-handling"></a>

# Error Handling

The client library throws the following exceptions:

## PSR Exceptions

### ClientExceptionInterface

Thrown according to the PSR-18 specification, if the HTTP client is unable to send the request at all or if the response
could not be parsed into a PSR-7 response object.

```php
Psr\Http\Client\ClientExceptionInterface
```

## Custom Exceptions

All custom exceptions will extend from the base `TrueLayer\Exceptions\Exception` class.

### ApiResponseUnsuccessfulException

Thrown if the API response is not a 2xx status.

```php
\TrueLayer\Exceptions\ApiResponseUnsuccessfulException

$e->getErrors(); // Get the errors provided by the API, as an array
$e->getStatusCode(); // The response status code
$e->getType(); // The error type, as a link to the TrueLayer docs
$e->getDetail(); // A description of the error message
$e->getTraceId(); // The TrueLayer error trace id
```

### ApiRequestJsonSerializationException

Thrown if the request data cannot be json encoded prior to calling the APIs.

```php
\TrueLayer\Exceptions\ApiRequestJsonSerializationException
```

### InvalidArgumentException

Thrown when a provided argument is invalid, for example an invalid beneficiary type

```php
\TrueLayer\Exceptions\InvalidArgumentException
```

### SignerException

Thrown if the request signer cannot be initialised or signing fails.

```php
\TrueLayer\Exceptions\SignerException
```

### EncryptException

Thrown when the client library fails to encrypt a payload that needs to be cached.

```php
\TrueLayer\Exceptions\EncryptException
```

### DecryptException

Thrown if the client library fails to decrypt the value of a cached key.

```php
\TrueLayer\Exceptions\DecryptException
```

### TLPublicKeysNotFound

Thrown when the webhook service cannot retrieve TL's public keys.

```php
\TrueLayer\Exceptions\TLPublicKeysNotFound
```

### WebhookHandlerException

Thrown when the webhook service is provided with an invalid handler.

```php
\TrueLayer\Exceptions\WebhookHandlerException
```

### WebhookHandlerInvalidArgumentException

Thrown when the webhook service cannot get the request body, signature header or the provided handlers have invalid
arguments.

```php
\TrueLayer\Exceptions\WebhookHandlerInvalidArgumentException
```

### WebhookVerificationFailedException

Thrown when the webhook signature cannot be verified.

```php
\TrueLayer\Exceptions\WebhookVerificationFailedException
```
