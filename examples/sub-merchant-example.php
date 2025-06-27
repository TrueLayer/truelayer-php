<?php

/**
 * Sub-Merchant Example
 * 
 * This example demonstrates how to create payments and payouts with sub-merchant
 * information for regulatory compliance and business transparency.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Initialize the TrueLayer client
$client = \TrueLayer\Client::configure()
    ->clientId($clientId)
    ->clientSecret($clientSecret)
    ->keyId($kid)
    ->pemFile($pemFilePath)
    ->create();

// Example 1: Payment with Business Client Sub-Merchant (using address)
function createPaymentWithBusinessClientAddress($client)
{
    // Create address
    $address = $client->address()
        ->addressLine1('123 Business Street')
        ->city('London')
        ->zip('SW1A 1AA')
        ->countryCode('GB');

    // Create ultimate counterparty (business client)
    $ultimateCounterparty = $client->ultimateCounterparty()
        ->businessClient()
        ->address($address)
        ->commercialName('My Business Ltd')
        ->mcc('5411'); // Grocery stores, supermarkets

    // Create sub-merchants container
    $subMerchants = $client->paymentSubMerchants()
        ->ultimateCounterparty($ultimateCounterparty);

    // Create payment with sub-merchants
    $payment = $client->payment()
        ->user($user)
        ->amountInMinor(1000) // £10.00
        ->currency(\TrueLayer\Constants\PaymentCurrencies::GBP)
        ->paymentMethod($paymentMethod);

    // Add sub-merchants to payment
    $payment->subMerchants($subMerchants);

    return $payment->create();
}

// Example 2: Payment with Business Client Sub-Merchant (using registration number)
function createPaymentWithBusinessClientRegistration($client)
{
    // Create ultimate counterparty with registration number
    $ultimateCounterparty = $client->ultimateCounterparty()
        ->businessClient()
        ->registrationNumber('12345678')
        ->commercialName('Registered Business Ltd')
        ->mcc('7372'); // Business services

    $subMerchants = $client->paymentSubMerchants()
        ->ultimateCounterparty($ultimateCounterparty);

    $payment = $client->payment()
        ->user($user)
        ->amountInMinor(2000) // £20.00
        ->currency(\TrueLayer\Constants\PaymentCurrencies::GBP)
        ->paymentMethod($paymentMethod);

    $payment->subMerchants($subMerchants);

    return $payment->create();
}

// Example 3: Payment with Business Division Sub-Merchant
function createPaymentWithBusinessDivision($client)
{
    // Create ultimate counterparty (business division)
    $ultimateCounterparty = $client->ultimateCounterparty()
        ->businessDivision()
        ->id('marketing-div')
        ->name('Marketing Division');

    $subMerchants = $client->paymentSubMerchants()
        ->ultimateCounterparty($ultimateCounterparty);

    $payment = $client->payment()
        ->user($user)
        ->amountInMinor(1500) // £15.00
        ->currency(\TrueLayer\Constants\PaymentCurrencies::GBP)
        ->paymentMethod($paymentMethod);

    $payment->subMerchants($subMerchants);

    return $payment->create();
}

// Example 4: Payout with Business Client Sub-Merchant
function createPayoutWithBusinessClient($client, $merchantAccount, $beneficiary)
{
    // Create address for payout sub-merchant
    $address = $client->address()
        ->addressLine1('789 Payout Street')
        ->city('Birmingham')
        ->zip('B1 1AA')
        ->countryCode('GB');

    // Create ultimate counterparty for payout
    $ultimateCounterparty = $client->ultimateCounterparty()
        ->businessClient()
        ->address($address)
        ->commercialName('Payout Business Ltd')
        ->mcc('6011'); // Financial institutions

    // Create payout sub-merchants (only business client supported)
    $subMerchants = $client->payoutSubMerchants()
        ->ultimateCounterparty($ultimateCounterparty);

    // Create payout with sub-merchants
    $payout = $client->payout()
        ->amountInMinor(500) // £5.00
        ->currency(\TrueLayer\Constants\PayoutCurrencies::GBP)
        ->merchantAccountId($merchantAccount->getId())
        ->beneficiary($beneficiary);

    // Add sub-merchants to payout
    $payout->subMerchants($subMerchants);

    return $payout->create();
}

// Example 5: Validation Rules Demo
function demonstrateValidationRules($client)
{
    try {
        // This will fail - business client needs either address OR registration number
        $invalidCounterparty = $client->ultimateCounterparty()
            ->businessClient()
            ->commercialName('Invalid Business') // No address or registration number
            ->mcc('5411');
        
        // This would throw an exception when used
    } catch (\InvalidArgumentException $e) {
        echo "Validation error: " . $e->getMessage() . "\n";
    }

    try {
        // This will fail - MCC must be 4 digits
        $invalidMcc = $client->ultimateCounterparty()
            ->businessClient()
            ->registrationNumber('12345678')
            ->mcc('invalid-mcc'); // Invalid format
    } catch (\InvalidArgumentException $e) {
        echo "MCC validation error: " . $e->getMessage() . "\n";
    }

    try {
        // This will fail - commercial name too long
        $longName = str_repeat('a', 101); // 101 characters, max is 100
        $invalidName = $client->ultimateCounterparty()
            ->businessClient()
            ->registrationNumber('12345678')
            ->commercialName($longName);
    } catch (\InvalidArgumentException $e) {
        echo "Name length validation error: " . $e->getMessage() . "\n";
    }
}

/*
 * Key Points:
 * 
 * 1. Business Client sub-merchants require either an address OR registration number
 * 2. MCC (Merchant Category Code) must be exactly 4 digits if provided
 * 3. Commercial name has a maximum length of 100 characters
 * 4. Registration number has a maximum length of 35 characters
 * 5. Business Division sub-merchants require both id and name
 * 6. Payouts only support business client sub-merchants (not business divisions)
 * 7. Sub-merchants are optional but may be required for certain payment flows
 * 8. The same sub-merchant structure applies to both payments and payouts
 */