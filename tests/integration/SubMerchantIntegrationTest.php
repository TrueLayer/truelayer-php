<?php

declare(strict_types=1);

use TrueLayer\Tests\Integration\Mocks\SubMerchantPaymentResponse;
use TrueLayer\Tests\Integration\Mocks\SubMerchantPayoutResponse;

// Ensure we have access to the helper functions
require_once __DIR__ . '/Pest.php';

\it('creates payment with business client sub-merchant and validates request payload', function (): void {
    $client = \client([SubMerchantPaymentResponse::createdWithBusinessClient()]);
    
    $address = $client->address()
        ->addressLine1('123 Test Street')
        ->city('London')
        ->zip('SW1A 1AA')
        ->countryCode('GB');
        
    $ultimateCounterparty = $client->ultimateCounterparty()
        ->businessClient()
        ->address($address)
        ->commercialName('Test Business Ltd')
        ->mcc('5411');
        
    $subMerchants = $client->paymentSubMerchants()
        ->ultimateCounterparty($ultimateCounterparty);
    
    $payment = $client->payment()
        ->amountInMinor(1000)
        ->currency('GBP')
        ->paymentMethod(
            $client->paymentMethod()
                ->bankTransfer()
                ->beneficiary(
                    $client->beneficiary()
                        ->externalAccount()
                        ->accountHolderName('Test Recipient')
                        ->reference('TEST')
                        ->accountIdentifier(
                            $client->accountIdentifier()
                                ->sortCodeAccountNumber()
                                ->sortCode('010203')
                                ->accountNumber('12345678')
                        )
                )
                ->providerSelection(
                    $client->providerSelection()
                        ->userSelected()
                        ->filter(
                            $client->providerFilter()
                                ->countries(['GB'])
                        )
                )
        )
        ->user(
            $client->user()
                ->name('Test User')
                ->email('test@example.com')
        );
        
    // Now we need to call subMerchants as a setter
    $payment->subMerchants($subMerchants);
    
    $created = $payment->create();
    
    \expect($created->getId())->toBe('5a2a0a0d-d3ad-4740-860b-45a01bcc17ac');
    
    // Verify request payload includes sub-merchants
    $payload = \getRequestPayload(1);
    \expect($payload)->toHaveKey('sub_merchants');
    \expect($payload['sub_merchants'])->toHaveKey('ultimate_counterparty');
    \expect($payload['sub_merchants']['ultimate_counterparty']['type'])->toBe('business_client');
    \expect($payload['sub_merchants']['ultimate_counterparty']['address'])->toMatchArray([
        'address_line1' => '123 Test Street',
        'city' => 'London',
        'zip' => 'SW1A 1AA',
        'country_code' => 'GB',
    ]);
    \expect($payload['sub_merchants']['ultimate_counterparty']['commercial_name'])->toBe('Test Business Ltd');
    \expect($payload['sub_merchants']['ultimate_counterparty']['mcc'])->toBe('5411');
});

\it('creates payment with business division sub-merchant and validates request payload', function (): void {
    $client = \client([SubMerchantPaymentResponse::createdWithBusinessDivision()]);
    
    $payment = $client->payment()
        ->amountInMinor(1500)
        ->currency('GBP')
        ->paymentMethod(
            $client->paymentMethod()
                ->bankTransfer()
                ->beneficiary(
                    $client->beneficiary()
                        ->externalAccount()
                        ->accountHolderName('Test Recipient')
                        ->reference('TEST')
                        ->accountIdentifier(
                            $client->accountIdentifier()
                                ->sortCodeAccountNumber()
                                ->sortCode('010203')
                                ->accountNumber('12345678')
                        )
                )
                ->providerSelection(
                    $client->providerSelection()
                        ->userSelected()
                        ->filter(
                            $client->providerFilter()
                                ->countries(['GB'])
                        )
                )
        )
        ->user(
            $client->user()
                ->name('Test User')
                ->email('test@example.com')
        )
        ->subMerchants(
            $client->paymentSubMerchants()
                ->ultimateCounterparty(
                    $client->ultimateCounterparty()
                        ->businessDivision()
                        ->id('div-123')
                        ->name('Marketing Division')
                )
        );
    
    $created = $payment->create();
    
    \expect($created->getId())->toBe('5a2a0a0d-d3ad-4740-860b-45a01bcc17ac');
    
    // Verify request payload includes business division sub-merchant
    $payload = \getRequestPayload(1);
    \expect($payload)->toHaveKey('sub_merchants');
    \expect($payload['sub_merchants']['ultimate_counterparty']['type'])->toBe('business_division');
    \expect($payload['sub_merchants']['ultimate_counterparty']['id'])->toBe('div-123');
    \expect($payload['sub_merchants']['ultimate_counterparty']['name'])->toBe('Marketing Division');
});

\it('creates payment with business client using registration number instead of address', function (): void {
    $client = \client([SubMerchantPaymentResponse::createdWithRegistrationNumber()]);
    
    $payment = $client->payment()
        ->amountInMinor(2000)
        ->currency('GBP')
        ->paymentMethod(
            $client->paymentMethod()
                ->bankTransfer()
                ->beneficiary(
                    $client->beneficiary()
                        ->externalAccount()
                        ->accountHolderName('Test Recipient')
                        ->reference('TEST')
                        ->accountIdentifier(
                            $client->accountIdentifier()
                                ->sortCodeAccountNumber()
                                ->sortCode('010203')
                                ->accountNumber('12345678')
                        )
                )
                ->providerSelection(
                    $client->providerSelection()
                        ->userSelected()
                        ->filter(
                            $client->providerFilter()
                                ->countries(['GB'])
                        )
                )
        )
        ->user(
            $client->user()
                ->name('Test User')
                ->email('test@example.com')
        )
        ->subMerchants(
            $client->paymentSubMerchants()
                ->ultimateCounterparty(
                    $client->ultimateCounterparty()
                        ->businessClient()
                        ->registrationNumber('12345678')
                        ->commercialName('Registered Business Ltd')
                        ->mcc('7372')
                )
        );
    
    $created = $payment->create();
    
    \expect($created->getId())->toBe('5a2a0a0d-d3ad-4740-860b-45a01bcc17ac');
    
    // Verify request payload uses registration number
    $payload = \getRequestPayload(1);
    \expect($payload['sub_merchants']['ultimate_counterparty'])->toMatchArray([
        'type' => 'business_client',
        'registration_number' => '12345678',
        'commercial_name' => 'Registered Business Ltd',
        'mcc' => '7372',
    ]);
    \expect($payload['sub_merchants']['ultimate_counterparty'])->not->toHaveKey('address');
});

\it('creates payout with business client sub-merchant and validates request payload', function (): void {
    $client = \client([SubMerchantPayoutResponse::createdWithBusinessClient()]);
    
    $payout = $client->payout()
        ->amountInMinor(500)
        ->currency('GBP')
        ->merchantAccountId('822f8dfe-0874-481d-b966-5b14f767792f')
        ->beneficiary(
            $client->payoutBeneficiary()
                ->externalAccount()
                ->accountHolderName('Test Recipient')
                ->reference('Test reference')
                ->accountIdentifier(
                    $client->accountIdentifier()
                        ->sortCodeAccountNumber()
                        ->sortCode('560029')
                        ->accountNumber('26207729')
                )
        )
        ->subMerchants(
            $client->payoutSubMerchants()
                ->ultimateCounterparty(
                    $client->ultimateCounterparty()
                        ->businessClient()
                        ->address(
                            $client->address()
                                ->addressLine1('789 Payout Street')
                                ->city('Birmingham')
                                ->zip('B1 1AA')
                                ->countryCode('GB')
                        )
                        ->commercialName('Payout Business Ltd')
                        ->mcc('6011')
                )
        );
    
    $created = $payout->create();
    
    \expect($created->getId())->toBe('ca9a3154-9151-44cf-b7cb-073c9e12ef91');
    
    // Verify request payload includes sub-merchants
    $payload = \getRequestPayload(1);
    \expect($payload)->toHaveKey('sub_merchants');
    \expect($payload['sub_merchants']['ultimate_counterparty']['type'])->toBe('business_client');
    \expect($payload['sub_merchants']['ultimate_counterparty']['address'])->toMatchArray([
        'address_line1' => '789 Payout Street',
        'city' => 'Birmingham',
        'zip' => 'B1 1AA',
        'country_code' => 'GB',
    ]);
    \expect($payload['sub_merchants']['ultimate_counterparty']['commercial_name'])->toBe('Payout Business Ltd');
    \expect($payload['sub_merchants']['ultimate_counterparty']['mcc'])->toBe('6011');
});

\it('creates payout with business client using registration number only', function (): void {
    $client = \client([SubMerchantPayoutResponse::createdWithRegistrationNumber()]);
    
    $payout = $client->payout()
        ->amountInMinor(750)
        ->currency('GBP')
        ->merchantAccountId('822f8dfe-0874-481d-b966-5b14f767792f')
        ->beneficiary(
            $client->payoutBeneficiary()
                ->externalAccount()
                ->accountHolderName('Test Recipient 2')
                ->reference('Test reference')
                ->accountIdentifier(
                    $client->accountIdentifier()
                        ->sortCodeAccountNumber()
                        ->sortCode('560029')
                        ->accountNumber('26207729')
                )
        )
        ->subMerchants(
            $client->payoutSubMerchants()
                ->ultimateCounterparty(
                    $client->ultimateCounterparty()
                        ->businessClient()
                        ->registrationNumber('87654321')
                        ->commercialName('Registered Payout Ltd')
                        ->mcc('7299')
                )
        );
    
    $created = $payout->create();
    
    \expect($created->getId())->toBe('ca9a3154-9151-44cf-b7cb-073c9e12ef91');
    
    // Verify request payload uses registration number
    $payload = \getRequestPayload(1);
    \expect($payload['sub_merchants']['ultimate_counterparty'])->toMatchArray([
        'type' => 'business_client',
        'registration_number' => '87654321',
        'commercial_name' => 'Registered Payout Ltd',
        'mcc' => '7299',
    ]);
    \expect($payload['sub_merchants']['ultimate_counterparty'])->not->toHaveKey('address');
});

\it('validates ultimate counterparty builder creates correct business client interface', function (): void {
    $client = \client([]);
    
    $businessClient = $client->ultimateCounterparty()
        ->businessClient()
        ->address(
            $client->address()
                ->addressLine1('Test Address')
                ->city('Test City')
                ->zip('TE1 1ST')
                ->countryCode('GB')
        )
        ->commercialName('Test Business')
        ->mcc('1234');
    
    \expect($businessClient)->toBeInstanceOf(\TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyBusinessClientInterface::class);
});

\it('validates ultimate counterparty builder creates correct business division interface', function (): void {
    $client = \client([]);
    
    $businessDivision = $client->ultimateCounterparty()
        ->businessDivision()
        ->id('test-div')
        ->name('Test Division');
    
    \expect($businessDivision)->toBeInstanceOf(\TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyBusinessDivisionInterface::class);
});

\it('validates payment sub-merchants builder creates correct interface', function (): void {
    $client = \client([]);
    
    $paymentSubMerchants = $client->paymentSubMerchants()
        ->ultimateCounterparty(
            $client->ultimateCounterparty()
                ->businessClient()
                ->address(
                    $client->address()
                        ->addressLine1('Test Address')
                        ->city('Test City')
                        ->zip('TE1 1ST')
                        ->countryCode('GB')
                )
                ->commercialName('Test Business')
                ->mcc('1234')
        );
    
    \expect($paymentSubMerchants)->toBeInstanceOf(\TrueLayer\Interfaces\SubMerchant\PaymentSubMerchantsInterface::class);
});

\it('validates payout sub-merchants builder creates correct interface', function (): void {
    $client = \client([]);
    
    $payoutSubMerchants = $client->payoutSubMerchants()
        ->ultimateCounterparty(
            $client->ultimateCounterparty()
                ->businessClient()
                ->registrationNumber('12345678')
                ->commercialName('Test Business')
                ->mcc('1234')
        );
    
    \expect($payoutSubMerchants)->toBeInstanceOf(\TrueLayer\Interfaces\SubMerchant\PayoutSubMerchantsInterface::class);
});

\it('validates address builder creates correct interface', function (): void {
    $client = \client([]);
    
    $address = $client->address()
        ->addressLine1('123 Test Street')
        ->city('London')
        ->zip('SW1A 1AA')
        ->countryCode('GB');
    
    \expect($address)->toBeInstanceOf(\TrueLayer\Interfaces\AddressInterface::class);
});