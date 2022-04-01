<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use TrueLayer\Constants\Countries;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\CustomerSegments;
use TrueLayer\Constants\ReleaseChannels;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Client\ClientInterface;
use TrueLayer\Interfaces\Payment\PaymentRequestInterface;
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
        $accountIdentifier = $this->client->accountIdentifier()
            ->sortCodeAccountNumber()
            ->accountNumber('12345678')
            ->sortCode('010203');

        return $this->client->beneficiary()
            ->externalAccount()
            ->accountIdentifier($accountIdentifier)
            ->reference('The ref')
            ->accountHolderName('John Doe');
    }

    /**
     * @return UserInterface
     */
    public function newUser(): UserInterface
    {
        return $this->client
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
        return $this->client->user()->id('64f800c1-ff48-411f-af78-464725376059');
    }

    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return BankTransferPaymentMethodInterface
     */
    public function bankTransferMethod(BeneficiaryInterface $beneficiary): BankTransferPaymentMethodInterface
    {
        $filter = $this->client
            ->providerFilter()
            ->countries([Countries::GB])
            ->customerSegments([CustomerSegments::RETAIL])
            ->releaseChannel(ReleaseChannels::PRIVATE_BETA)
            ->providerIds(['mock-payments-gb-redirect']);

        $selection = $this->client
            ->providerSelection()
            ->userSelected()
            ->filter($filter);

        return $this->client->paymentMethod()
            ->bankTransfer()
            ->beneficiary($beneficiary)
            ->providerSelection($selection);
    }

    /**
     * @param UserInterface          $user
     * @param PaymentMethodInterface $paymentMethod
     *
     * @return PaymentRequestInterface
     */
    public function payment(UserInterface $user, PaymentMethodInterface $paymentMethod): PaymentRequestInterface
    {
        return $this->client->payment()
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
        return new static(\client($responses));
    }
}
