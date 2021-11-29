<?php

namespace TrueLayer\Contracts\Models;

interface PaymentInterface
{
    /**
     * @return int|null
     */
    public function getAmountInMinor(): ?int;

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function amountInMinor(int $amount): self;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function currency(string $currency): self;

    /**
     * @return string|null
     */
    public function getStatementReference(): ?string;

    /**
     * @param string $statementReference
     *
     * @return $this
     */
    public function statementReference(string $statementReference): self;

    /**
     * @return BeneficiaryInterface|null
     */
    public function getBeneficiary(): ?BeneficiaryInterface;

    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return $this
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): self;

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface;

    /**
     * @param UserInterface $user
     *
     * @return $this
     */
    public function user(UserInterface $user): self;

    /**
     * @return array
     */
    public function toArray(): array;
}
