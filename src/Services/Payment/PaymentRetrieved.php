<?php

declare(strict_types=1);

namespace TrueLayer\Services\Payment;

use DateTime;
use Exception;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Services\Beneficiary\BeneficiaryBuilder;
use TrueLayer\Services\User;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Traits\WithSdk;

class PaymentRetrieved implements PaymentRetrievedInterface
{
    use WithSdk, HasAttributes;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->get('id');
    }

    /**
     * @return int
     */
    public function getAmountInMinor(): int
    {
        return $this->get('amount');
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->get('currency');
    }

    /**
     * @return string
     */
    public function getStatementReference(): string
    {
        return $this->get('payment_method.statement_reference');
    }

    /**
     * @return BeneficiaryInterface|null
     */
    public function getBeneficiary(): ?BeneficiaryInterface
    {
        $data = $this->get('beneficiary');

        return empty($data)
            ? null
            : BeneficiaryBuilder::make($this->getSdk())->fromArray($data);
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return User::make($this->getSdk())->fill(
            $this->get('user', [])
        );
    }

    /**
     * @throws Exception
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime(
            $this->get('created_at')
        );
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->get('status');
    }

    /**
     * @return bool
     */
    public function isAuthorizationRequired(): bool
    {
        return $this->getStatus() === PaymentStatus::AUTHORIZATION_REQUIRED;
    }

    /**
     * @return bool
     */
    public function isAuthorizing(): bool
    {
        return $this->getStatus() === PaymentStatus::AUTHORIZING;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->getStatus() === PaymentStatus::AUTHORIZED;
    }

    /**
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->getStatus() === PaymentStatus::EXECUTED;
    }

    /**
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->getStatus() === PaymentStatus::FAILED;
    }

    /**
     * @return bool
     */
    public function isSettled(): bool
    {
        return $this->getStatus() === PaymentStatus::SETTLED;
    }
}
