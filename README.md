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
    ->pemFile($pemFilePath)
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

### 4. Redirecting to the Hosted Payments Page

TrueLayer's Hosted Payment Page provides a high-converting UI for payment authorization that supports, out of the box, all action types.
You can easily redirect to it after creating your payment:

```php
$sdk->payment()
    ...
    ->create()
    ->hostedPaymentsPage()
    ->returnUri('http://www.mymerchantwebsite.com')
    ->primaryColor('#000000')
    ->secondaryColor('#e53935')
    ->tertiaryColor('#32329f')
    ->redirect();
```

You can also just get the URL if you'd like to manually redirect:

```php
$sdk->payment()
    ...
    ->create()
    ->hostedPaymentsPage()
    ->returnUri('http://www.mymerchantwebsite.com')
    ->toString();
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
if ($beneficiary instanceof \TrueLayer\Services\Beneficiary\SortCodeAccountNumber) {
    $beneficiary->getReference();
    $beneficiary->getAccountNumber();
    $beneficiary->getSortCode();
}
```

```php
if ($beneficiary instanceof \TrueLayer\Services\Beneficiary\IbanAccountBeneficiary) {
    $beneficiary->getReference();
    $beneficiary->getIban();
}
```

```php
if ($beneficiary instanceof \TrueLayer\Services\Beneficiary\MerchantAccountBeneficiary) {
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

TODO: List of exceptions