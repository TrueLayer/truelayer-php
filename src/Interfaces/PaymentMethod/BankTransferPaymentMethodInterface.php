<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\PaymentMethod;

use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Provider\ProviderSelectionInterface;

interface BankTransferPaymentMethodInterface extends PaymentMethodInterface
{
    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return BankTransferPaymentMethodInterface
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): BankTransferPaymentMethodInterface;

    /**
     * @return BeneficiaryInterface
     */
    public function getBeneficiary(): BeneficiaryInterface;

    /**
     * @param ProviderSelectionInterface $providerSelection
     *
     * @return BankTransferPaymentMethodInterface
     */
    public function providerSelection(ProviderSelectionInterface $providerSelection): BankTransferPaymentMethodInterface;

    /**
     * @return ProviderSelectionInterface
     */
    public function getProviderSelection(): ProviderSelectionInterface;
}
