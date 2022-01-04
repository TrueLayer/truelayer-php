<?php

declare(strict_types=1);

namespace TrueLayer\Services\Payment;

use DateTime;
use Exception;
use TrueLayer\Constants\Currencies;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Traits\WithSdk;
use TrueLayer\Validation\AllowedConstant;
use TrueLayer\Validation\ValidType;

final class PaymentRetrieved implements PaymentRetrievedInterface
{
    use WithSdk, HasAttributes;

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getId(): string
    {
        return $this->getString('id');
    }

    /**
     * @return int
     * @throws InvalidArgumentException
     */
    public function getAmountInMinor(): int
    {
        return $this->getInt('amount');
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getCurrency(): string
    {
        return $this->getString('currency');
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getStatementReference(): string
    {
        return $this->getString('payment_method.statement_reference');
    }

    /**
     * @return BeneficiaryInterface|null
     */
    public function getBeneficiary(): ?BeneficiaryInterface
    {
        $val = $this->get('beneficiary');
        return $val instanceof BeneficiaryInterface ? $val : null;
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        $val = $this->get('user');
        return $val instanceof UserInterface ? $val : null;
    }

    /**
     * @throws Exception
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime(
            $this->getString('created_at')
        );
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getStatus(): string
    {
        return $this->getString('status');
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isAuthorizationRequired(): bool
    {
        return $this->getStatus() === PaymentStatus::AUTHORIZATION_REQUIRED;
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isAuthorizing(): bool
    {
        return $this->getStatus() === PaymentStatus::AUTHORIZING;
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isAuthorized(): bool
    {
        return $this->getStatus() === PaymentStatus::AUTHORIZED;
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isExecuted(): bool
    {
        return $this->getStatus() === PaymentStatus::EXECUTED;
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isFailed(): bool
    {
        return $this->getStatus() === PaymentStatus::FAILED;
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isSettled(): bool
    {
        return $this->getStatus() === PaymentStatus::SETTLED;
    }

    /**
     * @return mixed[]
     */
    private function rules(): array
    {
        return [
            'id' => 'required|string',
            'status' => 'required|string',
            'created_at' => 'required|date',
            'amount_in_minor' => 'required|int|min:1',
            'currency' => ['required', 'string', AllowedConstant::in(Currencies::class)],
            'payment_method.type' => ['required', 'string', AllowedConstant::in(PaymentMethods::class)],
            'payment_method.statement_reference' => 'required|string',
            'user' => ['required', ValidType::of(UserInterface::class)],
            'beneficiary' => ['required', ValidType::of(BeneficiaryInterface::class)],
        ];
    }
}
