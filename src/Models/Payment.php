<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Contracts\Models\BeneficiaryInterface;
use TrueLayer\Contracts\Models\PaymentInterface;
use TrueLayer\Contracts\Models\UserInterface;

class Payment implements PaymentInterface
{
    /**
     * @var int|null
     */
    private ?int $amount = null;

    /**
     * @var string|null
     */
    private ?string $currency = null;

    /**
     * @var string|null
     */
    private ?string $statementReference = null;

    /**
     * @var BeneficiaryInterface|null
     */
    private ?BeneficiaryInterface $beneficiary = null;

    /**
     * @var UserInterface|null
     */
    private ?UserInterface $user = null;

    /**
     * @return int|null
     */
    public function getAmountInMinor(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function amountInMinor(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function currency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatementReference(): ?string
    {
        return $this->statementReference;
    }

    /**
     * @param string $statementReference
     *
     * @return $this
     */
    public function statementReference(string $statementReference): self
    {
        $this->statementReference = $statementReference;

        return $this;
    }

    /**
     * @return BeneficiaryInterface|null
     */
    public function getBeneficiary(): ?BeneficiaryInterface
    {
        return $this->beneficiary;
    }

    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return $this
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): self
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     *
     * @return $this
     */
    public function user(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'amount_in_minor' => $this->getAmountInMinor(),
            'currency' => $this->getCurrency(),
            'payment_method' => [
                'type' => PaymentMethods::BANK_TRANSFER,
                'statement_reference' => $this->getStatementReference(),
            ],
            'user' => $this->getUser()->toArray() ?? null,
            'beneficiary' => $this->getBeneficiary()->toArray() ?? null,
        ];
    }
}
