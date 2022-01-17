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
   2. [Get the beneficiary](#get-the-beneficiary)
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
      7. [Authorization flow config](#auth-flow-config)
7. [Custom API calls](#custom-api-calls)
8. [Error Handling](#error-handling)

<a name="why"></a>
## Why use this package?

This package simplifies working with the TrueLayer API, by:
1. Handling authentication, including token expiry, invalidation and caching
2. Signing requests
3. Managing idempotency keys, including retrying on conflicts
4. Retrying failed requests, where it makes sense to do so
5. Validating your data
6. Providing type-hinted methods and classes to work with

<a name="getting-started"></a>
## Getting started

### Installation 

```
composer require truelayer/sdk
```

### Initialisation

You will need to provide your own HTTP client that implements [PSR-18](https://www.php-fig.org/psr/psr-18/).
If your application does not already have one, you can use a client such as [Guzzle](https://docs.guzzlephp.org/en/stable/):

```php
composer require guzzlehttp/guzzle:^7.0

$httpClient = new \GuzzleHttp\Client();
```

You will also need to go to the TrueLayer console and create your credentials which you can then provide to the SDK configurator:

```php
$sdk = \TrueLayer\Sdk::configure()
    ->clientId($clientId)
    ->clientSecret($clientSecret)
    ->keyId($kid)
    ->pemFile($pemFilePath) // Or ->pem($contents) Or ->pemBase64($contents)
    ->httpClient($httpClient)
    ->create();
```

By default, the SDK will initialise in `sandbox` mode. To switch to production call `useProduction()`:

```php
$sdk = \TrueLayer\Sdk::configure()
    ...
    ->useProduction() // optionally, pass a boolean flag to toggle between production/sandbox mode.
    ->create(); 
```

<a name="caching"></a>
## Caching

The SDK supports caching the `client_credentials` grant access token needed to access, create and modify resources on TrueLayer's systems.
In order to enable it, you need to provide an implementation of the [PSR-16](https://www.php-fig.org/psr/psr-16/) common caching interface and a 32-bytes encryption key.

You can generate a random encryption key by running `openssl rand -hex 16`. This key must be considered secret and stored next to the client secrets obtained from TrueLayer's console.

```php
$sdk = \TrueLayer\Sdk::configure()
    ...
    ->cache(\Illuminate\Support\Facades\App::make('cache')->driver(), $encryptionKey)
    ->create();
```

A good example of a caching library that implements PSR-16 is [illuminate/cache](https://github.com/illuminate/cache).

<a name="arrays"></a>
## Converting to and from arrays

If you want to skip calling each setter method, you can use arrays to create any resource:

```php
$sdk->beneficiary()->fill($beneficiaryData);
$sdk->user()->fill($userData);
$sdk->payment()->fill($paymentData);
// etc...
```

You can also convert any resource to array. This can be convenient if you need to output it to json for example:

```php
$paymentData = $sdk->getPayment($paymentId)->toArray(); 
```

<a name="creating-a-payment"></a>
## Creating a payment

<a name="creating-a-beneficiary"></a>
### 1. Creating a beneficiary

```php
$beneficiary = $sdk->beneficiary()
    ->sortCodeAccountNumber()
    ->accountNumber('12345678')
    ->sortCode('010203')
    ->reference('The reference')
    ->name('John Doe');
```

<a name="creating-a-user"></a>
### 2. Creating a user

```php
$user = $sdk->user()
    ->name('Jane Doe')
    ->email('jane.doe@truelayer.com');
```

<a name="creating-a-payment-method"></a>
### 3. Creating a payment method

```php
$paymentMethod = $sdk->paymentMethod()
    ->type(\TrueLayer\Constants\PaymentMethods::BANK_TRANSFER)
    ->statementReference('Reference');
```

<a name="creating-the-payment"></a>
### 4. Creating the payment

```php
$payment = $sdk->payment()
    ->beneficiary($beneficiary)
    ->user($user)
    ->amountInMinor(1)
    ->currency(\TrueLayer\Constants\Currencies::GBP) // You can use other currencies defined in this class.
    ->paymentMethod($paymentMethod)
    ->create();
```

You then get access to the following methods:

```php
$payment->getId(); // The payment id
$payment->getResourceToken(); // The resource token 
$payment->getDetails(); // Get the payment details, same as $sdk->getPayment($paymentId)
$payment->hostedPaymentsPage(); // Get the Hosted Payments Page helper, see below.
$payment->toArray(); // Convert to array
```

<a name="creating-a-payment-from-array"></a>
### 5. Creating a payment from an array

If you prefer, you can work directly with arrays by calling the `fill` method:

```php
$paymentData = [
    'amount_in_minor' => 1,
    'currency' => \TrueLayer\Constants\Currencies::GBP,
    'user' => [
        'name' => 'Jane Doe',
        'email' => 'jane@doe.com'
    ],
    'beneficiary' => [
        'type' => \TrueLayer\Constants\BeneficiaryTypes::EXTERNAL_ACCOUNT,
        'name' => 'John Doe',
        'reference' => 'Payment',
        'scheme_identifier' => [
            'type' => \TrueLayer\Constants\ExternalAccountTypes::SORT_CODE_ACCOUNT_NUMBER,
            'sort_code' => '010203',
            'account_number' => '12345678',
        ]
    ],
    'payment_method' => [
        'type' => \TrueLayer\Constants\PaymentMethods::BANK_TRANSFER,
        'statement_reference' => 'Reference',
        'provider_filter' => [
            'countries' => [
                \TrueLayer\Constants\Countries::GB,
            ],
            'release_channel' => \TrueLayer\Constants\ReleaseChannels::GENERAL_AVAILABILITY,
            'customer_segments' => [
                \TrueLayer\Constants\CustomerSegments::RETAIL,
                \TrueLayer\Constants\CustomerSegments::BUSINESS,
            ],
            'provider_ids' => [
                'mock-payments-gb-redirect',
            ],
        ],
    ],
];

$payment = $sdk->payment()->fill($paymentData)->create();
```

<a name="redirect-to-hpp"></a>
### 6. Redirecting to the Hosted Payments Page

TrueLayer's Hosted Payment Page provides a high-converting UI for payment authorization that supports, out of the box, all action types.
You can easily get the URL to redirect to after creating your payment:

```php
$url = $sdk->payment()
    ...
    ->create()
    ->hostedPaymentsPage()
    ->returnUri('http://www.mymerchantwebsite.com')
    ->primaryColour('#000000')
    ->secondaryColour('#e53935')
    ->tertiaryColour('#32329f')
    ->toUrl();
```

<a name="retrieving-a-payment"></>
# Retrieving a payment's details

```php
$payment = $sdk->getPayment($paymentId);
$payment->getId();
$payment->getAmountInMinor();
$payment->getCreatedAt(); // The date and time the payment was executed at
$payment->getCurrency();
$payment->getPaymentMethod();
$payment->toArray();
```

<a name="get-the-user"></a>
## Get the user

```php
$user = $sdk->getPayment($paymentId)->getUser();
$user->getId();
$user->getName();
$user->getEmail();
$user->getPhone();
$user->toArray();
```

<a name="get-the-beneficiary"></a>
## Get the beneficiary

There are multiple types of payment beneficiaries. They all implement `BeneficiaryInterface` so you can access:

```php
$beneficiary = $sdk->getPayment($paymentId)->getBeneficiary();
$beneficiary->getName();
$beneficiary->getType();
$beneficiary->toArray();
```

Depending on the beneficiary type, you can then access specific methods:

```php
if ($beneficiary instanceof \TrueLayer\Models\Beneficiary\ScanBeneficiary) {
    $beneficiary->getReference();
    $beneficiary->getAccountNumber();
    $beneficiary->getSortCode();
}
```

```php
if ($beneficiary instanceof \TrueLayer\Models\Beneficiary\IbanBeneficiary) {
    $beneficiary->getReference();
    $beneficiary->getIban();
}
```

```php
if ($beneficiary instanceof \TrueLayer\Models\Beneficiary\MerchantBeneficiary) {
    $beneficiary->getId();
}
```

<a name="check-payment-status"></a>
## Check a payment's status

You can check for the status by using one of the following helper methods:

```php
$payment = $sdk->getPayment($paymentId);
$payment->isAuthorizationRequired();
$payment->isAuthorizing();
$payment->isAuthorized(); // Will also return false when the payment has progressed to executed, failed or settled states.
$payment->isExecuted(); // Will also return false when the payment has progressed to failed or settled states.
$payment->isSettled(); 
$payment->isFailed();
```

Or you can get the status as a string and compare it to the provided constants in `PaymentStatus`:
```php
$payment = $sdk->getPayment($paymentId);
$payment->getStatus() === \TrueLayer\Constants\PaymentStatus::AUTHORIZATION_REQUIRED;
```

<a name="payment-status-specific-fields"></a>
## Working with status specific payment fields

<a name="status-authorization-required"></a>
### Authorization Required Status

> Payment with this status is on its initial phase where no action beyond the creation of the payment was taken.

```php
use TrueLayer\Contracts\Payment\PaymentAuthorizationRequiredInterface;

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
use TrueLayer\Contracts\Payment\PaymentAuthorizingInterface;

if ($payment instanceof PaymentAuthorizingInterface) {
    $payment->getAuthorizationFlowConfig(); // see authorization flow config
    
    // Will return a \TrueLayer\Contracts\Payment\AuthorizationFlow\Action\ActionInterface
    $payment->getAuthorizationFlowNextAction(); 
}
```

<a name="action-provider-selection"></a>
#### Provider selection action

This action indicates that the user needs to select a provider from the provided list.
To render the provider list, each provider comes with helpful methods for retrieving the name, logo, id, etc.

```php
use TrueLayer\Contracts\Payment\AuthorizationFlow\Action\ProviderSelectionActionInterface;

$nextAction = $payment->getAuthorizationFlowNextAction();

if ($nextAction instanceof ProviderSelectionActionInterface) {
    foreach ($nextAction->getProviders() as $provider) {
        $provider->getProviderId();
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
use TrueLayer\Contracts\Payment\AuthorizationFlow\Action\RedirectActionInterface;

$nextAction = $payment->getAuthorizationFlowNextAction();

if ($nextAction instanceof RedirectActionInterface) {
    $nextAction->getUri(); // The URL the end user must be redirected to
    $nextAction->getProvider(); // The provider object, see available methods above.
}
```

<a name="action-wait"></a>
#### Wait action

```php
use TrueLayer\Contracts\Payment\AuthorizationFlow\Action\WaitActionInterface;

$nextAction = $payment->getAuthorizationFlowNextAction();

if ($nextAction instanceof WaitActionInterface) {
    // your logic here   
}
```

<a name="status-authorized"></a>
### Authorized Status

> Payment has successfully completed its authorization flow

```php
use TrueLayer\Contracts\Payment\PaymentAuthorizedInterface;

if ($payment instanceof PaymentAuthorizedInterface) {
    $payment->getAuthorizationFlowConfig(); // see authorization flow config
}
```

<a name="status-executed"></a>
### Executed Status

> Payment has been accepted by the bank

```php
use TrueLayer\Contracts\Payment\PaymentExecutedInterface;

if ($payment instanceof PaymentExecutedInterface) {
    $payment->getExecutedAt(); // The date and time the payment was executed at
    $payment->getAuthorizationFlowConfig(); // see authorization flow config
}
```

<a name="status-settled"></a>
### Settled Status

> Payment can transition into this state if the beneficiary account was a merchant account within Truelayer, and Truelayer has observed the money to be settled.

```php
use TrueLayer\Contracts\Payment\PaymentSettledInterface;

if ($payment instanceof PaymentSettledInterface) {
    $payment->getExecutedAt(); // The date and time the payment was executed at
    $payment->getSettledAt(); // The date and time the payment was settled at
    $payment->getAuthorizationFlowConfig(); // see authorization flow config
}
```

<a name="status-failed"></a>
### Failed Status

> Payment has failed. The reason for failure can be observed in failure_reason field on the payment resource

```php
use TrueLayer\Contracts\Payment\PaymentFailedInterface;

if ($payment instanceof PaymentFailedInterface) {
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
use TrueLayer\Contracts\Payment\PaymentExecutedInterface;

if ($payment instanceof PaymentExecutedInterface) {
    $config = $payment->getAuthorizationFlowConfig(); 
    $config->isRedirectSupported() // Is redirect supported or not
    $config->getRedirectReturnUri(); // The URL the user will be redirected back once the flow on the third-party's website is completed
    $config->isProviderSelectionSupported(); // Is provider selection supported or not
}
```

<a name="custom-api-calls"></a>
# Custom API calls

You can use the SDK to make your own API calls without worrying about authentication or request signing:

```php
$responseData = $sdk->getApiClient()->request()->uri('/merchant-accounts')->get();

$responseData = $sdk->getApiClient()->request()
    ->uri('/payments')
    ->payload($myData)
    ->header('My Header', 'value')
    ->post();
```

<a name="error-handling"></a>
# Error Handling

The SDK throws the following exceptions:

## PSR Exceptions

### ClientExceptionInterface

Thrown according to the PSR-18 specification, if the HTTP client is unable to send the request at all or if the response could not be parsed into a PSR-7 response object.

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

### ValidationException

Thrown if the data you provide to the SDK or the API response data is invalid.

```php
\TrueLayer\Exceptions\ValidationException

$e->getErrors(); // Get the validation errors as an array
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

Thrown when the SDK fails to encrypt a payload that needs to be cached.
 
```php
\TrueLayer\Exceptions\EncryptException
```

### DecryptException

Thrown if the SDK fails to decrypt the value of a cached key.

```php
\TrueLayer\Exceptions\DecryptException
```
