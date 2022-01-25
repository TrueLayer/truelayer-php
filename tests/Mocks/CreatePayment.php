<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Mocks;

use TrueLayer\Constants\Countries;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\CustomerSegments;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Constants\ReleaseChannels;
use TrueLayer\Entities\Beneficiary\ScanBeneficiary;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Payment\PaymentMethodInterface;
use TrueLayer\Interfaces\Payment\PaymentRequestInterface;
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
     * @return PaymentMethodInterface
     */
    public function paymentMethod(): PaymentMethodInterface
    {
        $filter = $this->sdk
            ->providerFilter()
            ->countries([Countries::GB])
            ->customerSegments([CustomerSegments::RETAIL])
            ->releaseChannel(ReleaseChannels::PRIVATE_BETA)
            ->providerIds(['mock-payments-gb-redirect']);

        return $this->sdk->paymentMethod()
            ->type(PaymentMethods::BANK_TRANSFER)
            ->statementReference('Statement ref')
            ->providerFilter($filter);
    }

    /**
     * @param BeneficiaryInterface   $beneficiary
     * @param UserInterface          $user
     * @param PaymentMethodInterface $paymentMethod
     *
     * @return PaymentRequestInterface
     */
    public function payment(BeneficiaryInterface $beneficiary, UserInterface $user, PaymentMethodInterface $paymentMethod): PaymentRequestInterface
    {
        return $this->sdk->payment()
            ->beneficiary($beneficiary)
            ->user($user)
            ->amountInMinor(1)
            ->currency(Currencies::GBP)
            ->paymentMethod($paymentMethod);
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
