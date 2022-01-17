## Why use this package?

This package simplifies working with the TrueLayer API, by:
1. Handling authentication, including token expiry, invalidation and caching
2. Signing requests
3. Managing idempotency keys, including retrying on conflicts
4. Retrying failed requests, where it makes sense to do so
5. Validating your data
6. Providing type-hinted methods and classes to work with

## Installing with composer

```
composer require truelayer/sdk
```

## Initialising the SDK

You will need to go to the TrueLayer console and create your credentials which tou can then to the SDK configurator. 
You will also need to provide an HTTP client that implements [PSR-18](https://www.php-fig.org/psr/psr-18/):

```php
$sdk = \TrueLayer\Sdk::configure()
    ->clientId($clientId)
    ->clientSecret($clientSecret)
    ->keyId($kid)
    ->pemFile($pemFilePath) // Or ->pem($contents) Or ->pemBase64($contents)
    ->httpClient(new \GuzzleHttp\Client())
    ->create();
```

By default, the SDK will initialise in `sandbox` mode. To switch to production call `useProduction()`:

```php
$sdk = \TrueLayer\Sdk::configure()
    ...
    ->useProduction() // optionally, pass a boolean flag to toggle between production/sandbox mode.
    ->create(); 
```

## Creating a payment

### 1. Creating a beneficiary

```php
$beneficiary = $sdk->beneficiary()
    ->sortCodeAccountNumber()
    ->accountNumber('12345678')
    ->sortCode('010203')
    ->reference('The reference')
    ->name('John Doe');
```

### 2. Creating a user

```php
$user = $sdk->user()
    ->name('Jane Doe')
    ->email('jane.doe@truelayer.com');
```

### 3. Creating a payment method

```php
$paymentMethod = \TrueLayer\Models\Payment\PaymentMethod::make($sdk)->fill([
    'type' => \TrueLayer\Constants\PaymentMethods::BANK_TRANSFER,
    'statement_reference' => 'Reference',
]);
```

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

### 5. Working with arrays

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

# Retrieving a payment's details

```php
$payment = $sdk->getPayment($paymentId);
$payment->getId();
$payment->getAmountInMinor();
$payment->getCreatedAt();
$payment->getCurrency();
$payment->getPaymentMethod();
$payment->toArray();
```

## Get a payment's user

```php
$user = $sdk->getPayment($paymentId)->getUser();
$user->getId();
$user->getName();
$user->getEmail();
$user->getPhone();
$user->toArray();
```

## Get a payment's beneficiary

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
