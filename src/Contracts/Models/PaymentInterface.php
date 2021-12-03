<?php

namespace TrueLayer\Contracts\Models;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\ArrayFactoryInterface;

interface PaymentInterface extends ArrayableInterface, ArrayFactoryInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

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
}
