<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Acceptance\Payment;

use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\MerchantBeneficiaryInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Interfaces\Payment\PaymentCreatedInterface;
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;
use TrueLayer\Interfaces\Sdk\SdkInterface;
use TrueLayer\Interfaces\UserInterface;

class CreatePayment
{
    private SdkInterface $sdk;

    /**
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        $this->sdk = $sdk;
    }

    /**
     * @return ExternalAccountBeneficiaryInterface
     */
    public function sortCodeBeneficiary(): ExternalAccountBeneficiaryInterface
    {
        return $this->sdk->beneficiary()->externalAccount()
            ->reference('TEST')
            ->accountHolderName('John SCAN')
            ->accountIdentifier($this->sdk->accountIdentifier()
                ->sortCodeAccountNumber()
                ->accountNumber('12345678')
                ->sortCode('010203')
            );
    }

    public function ibanBeneficiary(): ExternalAccountBeneficiaryInterface
    {
        return $this->sdk->beneficiary()->externalAccount()
            ->reference('TEST')
            ->accountHolderName('John IBAN')
            ->accountIdentifier($this->sdk->accountIdentifier()
                ->iban()
                ->iban('GB53CLRB04066200002723')
            );
    }

    public function merchantBeneficiary(MerchantAccountInterface $account): MerchantBeneficiaryInterface
    {
        return $this->sdk->beneficiary()->merchantAccount($account);
    }

    /**
     * @return UserInterface
     */
    public function user(): UserInterface
    {
        return $this->sdk
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
        return $this->sdk->paymentMethod()
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

        return $this->sdk->payment()
            ->paymentMethod($paymentMethod)
            ->amountInMinor(1)
            ->currency($currency)
            ->user($user ?? $this->user())
            ->create();
    }

    /**
     * @return SdkInterface
     */
    public function sdk(): SdkInterface
    {
        return $this->sdk;
    }
}
