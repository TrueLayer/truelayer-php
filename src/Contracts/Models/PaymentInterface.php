<?php

namespace TrueLayer\Contracts\Models;

interface PaymentInterface
{
    /**
     * @param int $amount
     * @return $this
     */
    public function amountInMinor(int $amount): self;

    /**
     * @param string $currency
     * @return $this
     */
    public function currency(string $currency): self;

    /**
     * @param string $statementReference
     * @return $this
     */
    public function statementReference(string $statementReference): self;

    /**
     * @param BeneficiaryInterface $beneficiary
     * @return $this
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): self;

    /**
     * @param UserInterface $user
     * @return $this
     */
    public function user(UserInterface $user): self;

    /**
     * @return array
     */
    public function toArray(): array;
}
