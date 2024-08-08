<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Acceptance\Payment;

use Error;
use TrueLayer\Constants\SchemeSelectionTypes;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\MerchantBeneficiaryInterface;
use TrueLayer\Interfaces\Client\ClientInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;
use TrueLayer\Interfaces\Provider\PreselectedProviderSelectionInterface;
use TrueLayer\Interfaces\Remitter\RemitterInterface;
use TrueLayer\Interfaces\Scheme\SchemeSelectionInterface;
use TrueLayer\Interfaces\UserInterface;

class CreatePayment
{
    private ClientInterface $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return ExternalAccountBeneficiaryInterface
     */
    public function sortCodeBeneficiary(): ExternalAccountBeneficiaryInterface
    {
        return $this->client->beneficiary()->externalAccount()
            ->reference('TEST')
            ->accountHolderName('John SCAN')
            ->accountIdentifier($this->client->accountIdentifier()
                ->sortCodeAccountNumber()
                ->accountNumber('12345678')
                ->sortCode('010203')
            );
    }

    public function ibanBeneficiary(): ExternalAccountBeneficiaryInterface
    {
        return $this->client->beneficiary()->externalAccount()
            ->reference('TEST')
            ->accountHolderName('John IBAN')
            ->accountIdentifier($this->client->accountIdentifier()
                ->iban()
                ->iban('GB53CLRB04066200002723')
            );
    }

    public function merchantBeneficiary(MerchantAccountInterface $account): MerchantBeneficiaryInterface
    {
        return $this->client->beneficiary()->merchantAccount($account)->reference('TEST');
    }

    /**
     * @return PreselectedProviderSelectionInterface
     */
    public function providerSelectionPreselected(): PreselectedProviderSelectionInterface
    {
        return $this->client
            ->providerSelection()
            ->preselected()
            ->providerId('mock-payments-gb-redirect');
    }

    /**
     * @return RemitterInterface
     */
    public function remitter(): RemitterInterface
    {
        return $this->client
            ->remitter()
            ->accountIdentifier(
                $this->client()
                    ->accountIdentifier()
                    ->iban()
                    ->iban('GB53CLRB04066200002723')
            )
            ->accountHolderName('John Doe');
    }

    public function schemeSelection(string $type): SchemeSelectionInterface
    {
        if ($type == SchemeSelectionTypes::PRESELECTED) {
            return $this->client
                ->schemeSelection()
                ->preselected()
                ->schemeId('faster_payments_service');
        }

        throw new Error('Unknown scheme selection type');
    }

    /**
     * @return UserInterface
     */
    public function user(): UserInterface
    {
        return $this->client
            ->user()
            ->name('Alice')
            ->phone('+447837485713')
            ->email('alice@truelayer.com');
    }

    public function userWithAddress(): UserInterface
    {
        $user = $this->user();
        $user->address()
            ->addressLine1('The Gilbert')
            ->city('London')
            ->state('London')
            ->zip('EC2A 1PX')
            ->countryCode('GB');

        return $user;
    }

    public function userWithDateOfBirth(string $date): UserInterface
    {
        return $this->user()
            ->dateOfBirth($date);
    }

    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return BankTransferPaymentMethodInterface
     */
    public function bankTransferMethod(BeneficiaryInterface $beneficiary): BankTransferPaymentMethodInterface
    {
        return $this->client->paymentMethod()
            ->bankTransfer()
            ->beneficiary($beneficiary);
    }

    /**
     * @param PaymentMethodInterface|null $paymentMethod
     * @param UserInterface|null          $user
     * @param string                      $currency
     *
     * @return PaymentCreatedInterface
     */
    public function create(PaymentMethodInterface $paymentMethod = null, UserInterface $user = null, string $currency = 'GBP'): PaymentCreatedInterface
    {
        if (!$paymentMethod) {
            $paymentMethod = $this->bankTransferMethod($this->sortCodeBeneficiary());
        }

        return $this->client->payment()
            ->paymentMethod($paymentMethod)
            ->amountInMinor(10)
            ->metadata([
                'metadata_key_1' => 'metadata_value_1',
                'metadata_key_2' => 'metadata_value_2',
                'metadata_key_3' => 'metadata_value_3',
            ])
            ->currency($currency)
            ->user($user ?? $this->user())
            ->create();
    }

    /**
     * @return ClientInterface
     */
    public function client(): ClientInterface
    {
        return $this->client;
    }
}
