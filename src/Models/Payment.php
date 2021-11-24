<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Contracts\Models\BeneficiaryInterface;
use TrueLayer\Contracts\Models\PaymentInterface;
use TrueLayer\Contracts\Models\UserInterface;

class Payment implements PaymentInterface
{
    /**
     * @var int
     */
    private int $amount;

    /**
     * @var string
     */
    private string $currency;

    /**
     * @var string
     */
    private string $statementReference;

    /**
     * @var BeneficiaryInterface
     */
    private BeneficiaryInterface $beneficiary;

    /**
     * @var UserInterface
     */
    private UserInterface $user;

    /**
     * @param int $amount
     * @return $this
     */
    public function amountInMinor(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function currency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $statementReference
     * @return $this
     */
    public function statementReference(string $statementReference): self
    {
        $this->statementReference = $statementReference;
        return $this;
    }

    /**
     * @param BeneficiaryInterface $beneficiary
     * @return $this
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): self
    {
        $this->beneficiary = $beneficiary;
        return $this;
    }

    /**
     * @param UserInterface $user
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
            'amount_in_minor' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => [
                'type' => 'bank_transfer',
                'statement_reference' => $this->statementReference,
            ],
            'user' => $this->user->toArray(),
            'beneficiary' => $this->beneficiary->toArray(),
        ];
    }
}
