<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Acceptance\Payment;

use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\MerchantBeneficiaryInterface;
use TrueLayer\Interfaces\Client\ClientInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;
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
        return $this->client->beneficiary()->merchantAccount($account);
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
