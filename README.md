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
composer require @truelayer/php-sdk
```

## Initialising the SDK

You will need to go to the TrueLayer console and create your credentials. You can then pass them to the SDK configurator:

```php
$sdk = \TrueLayer\Sdk::configure()
    ->clientId($clientId)
    ->clientSecret($clientSecret)
    ->keyId($kid)
    ->pemFile($pemFilePath) // Or ->pem($contents)
    ->create();
```

By default, the SDK will initialise in `sandbox` mode. To switch to production call `useProduction()`:

```php
$sdk = \TrueLayer\Sdk::configure()
    ...
    ->useProduction(); // optionally, pass a boolean flag to toggle between production/sandbox mode.
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

### 3. Creating the payment

```php
$payment = $sdk->payment()
    ->beneficiary($beneficiary)
    ->user($user)
    ->amountInMinor(1)
    ->currency(\TrueLayer\Constants\Currencies::GBP) // You can use other currencies defined in this class.
    ->statementReference('Statement reference')
    ->create();
```

You then get access to the following methods:

```php
$payment->getId(); // The payment id
$payment->getResourceToken(); // The resource token 
$payment->getDetails(); // Get the payment details, same as $sdk->getPaymentDetails($paymentId)
$payment->hostedPaymentsPage(); // Get the Hosted Payments Page helper, see below.
$payment->toArray(); // Convert to array
```

### 4. Working with arrays

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
        'statement_reference' => 'Reference',
    ],
];

$payment = $sdk->payment()->fill($paymentData)->create();
```

### 5. Redirecting to the Hosted Payments Page

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
$payment = $sdk->getPaymentDetails($paymentId);
$payment->getId();
$payment->getAmountInMinor();
$payment->getCreatedAt();
$payment->getCurrency();
$payment->getStatementReference();
$payment->toArray();
```

## Get a payment's user

```php
$user = $sdk->getPaymentDetails($paymentId)->getUser();
$user->getId();
$user->getName();
$user->getEmail();
$user->getPhone();
$user->toArray();
```

## Get a payment's beneficiary

There are multiple types of payment beneficiaries. They all implement `BeneficiaryInterface` so you can access:

```php
$beneficiary = $sdk->getPaymentDetails($paymentId)->getBeneficiary();
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
$payment = $sdk->getPaymentDetails($paymentId);
$payment->isAuthorizationRequired();
$payment->isAuthorizing();
$payment->isAuthorized();
$payment->isExecuted();
$payment->isFailed();
$payment->isSettled();
```

Or you can get the status as a string and compare it to the provided constants in `PaymentStatus`:
```php
$payment = $sdk->getPaymentDetails($paymentId);
$payment->getStatus() === \TrueLayer\Constants\PaymentStatus::AUTHORIZATION_REQUIRED;
```

# Error Handling

The SDK throws the following exceptions:

## ClientExceptionInterface

Thrown according to the PSR-18 specification, if it is unable to send the HTTP request at all or if the HTTP response could not be parsed into a PSR-7 response object.

```php
Psr\Http\Client\ClientExceptionInterface
```

## ApiResponseUnsuccessfulException
Thrown if the API response is not a 2xx status.

```php
\TrueLayer\Exceptions\ApiResponseUnsuccessfulException

$e->getErrors(); // Get the errors provided by the API, as an array
$e->getStatusCode(); // The response status code
$e->getType(); // The error type, as a link to the TrueLayer docs
$e->getDetail(); // A description of the error message
$e->getTraceId(); // The TrueLayer error trace id
```

## ApiRequestJsonSerializationException

Thrown if the request data cannot be json encoded prior to calling the APIs.

```php
\TrueLayer\Exceptions\ApiRequestJsonSerializationException
```

## ValidationException

Thrown if the data you provide to the SDK or the API response data is invalid.

```php
\TrueLayer\Exceptions\ValidationException

$e->getErrors(); // Get the validation errors as an array
```

## InvalidArgumentException

Thrown when a provided argument is invalid, for example an invalid pem file path.

```php
\TrueLayer\Exceptions\InvalidArgumentException
```

