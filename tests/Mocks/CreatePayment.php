<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Mocks;

use TrueLayer\Constants\Currencies;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\PaymentRequestInterface;
use TrueLayer\Contracts\Sdk\SdkInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Models\Beneficiary\ScanBeneficiary;

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
     * @return ScanBeneficiary
     */
    public function sortCodeBeneficiary(): ScanBeneficiary
    {
        return $this->sdk
            ->beneficiary()
            ->sortCodeAccountNumber()
            ->accountNumber('12345678')
            ->sortCode('010203')
            ->reference('The ref')
            ->name('John Doe');
    }

    /**
     * @return UserInterface
     */
    public function newUser(): UserInterface
    {
        return $this->sdk
            ->user()
            ->name('Alice')
            ->phone('+447837485713')
            ->email('alice@truelayer.com');
    }

    /**
     * @return UserInterface
     */
    public function existingUser(): UserInterface
    {
        return $this->sdk->user()->id('64f800c1-ff48-411f-af78-464725376059');
    }

    /**
     * @param BeneficiaryInterface $beneficiary
     * @param UserInterface        $user
     *
     * @return PaymentRequestInterface
     */
    public function payment(BeneficiaryInterface $beneficiary, UserInterface $user): PaymentRequestInterface
    {
        return $this->sdk->payment()
            ->beneficiary($beneficiary)
            ->user($user)
            ->amountInMinor(1)
            ->currency(Currencies::GBP)
            ->statementReference('Statement ref');
    }

    /**
     * @param array $responses
     *
     * @return static
     */
    public static function responses(array $responses): self
    {
        return new static(\sdk($responses));
    }
}
