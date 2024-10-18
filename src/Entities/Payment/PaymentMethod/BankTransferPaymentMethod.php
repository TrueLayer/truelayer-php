<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentMethod;

use TrueLayer\Attributes\Field;
use TrueLayer\Constants\PaymentMethods;
use TrueLayer\Entities\Entity;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\PaymentMethod\BankTransferPaymentMethodInterface;
use TrueLayer\Interfaces\Provider\ProviderSelectionInterface;
use TrueLayer\Interfaces\Provider\UserSelectedProviderSelectionInterface;

class BankTransferPaymentMethod extends Entity implements BankTransferPaymentMethodInterface
{
    /**
     * @var string
     */
    #[Field]
    protected string $type;

    /**
     * @var BeneficiaryInterface
     */
    #[Field]
    protected BeneficiaryInterface $beneficiary;

    /**
     * @var ProviderSelectionInterface
     */
    #[Field]
    protected ProviderSelectionInterface $providerSelection;

    /**
     * @var \stdClass|null
     */
    #[Field]
    protected ?\stdClass $retry;

    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return BankTransferPaymentMethodInterface
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): BankTransferPaymentMethodInterface
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    /**
     * @return BeneficiaryInterface
     */
    public function getBeneficiary(): BeneficiaryInterface
    {
        return $this->beneficiary;
    }

    /**
     * @param ProviderSelectionInterface $providerSelection
     *
     * @return BankTransferPaymentMethodInterface
     */
    public function providerSelection(ProviderSelectionInterface $providerSelection): BankTransferPaymentMethodInterface
    {
        $this->providerSelection = $providerSelection;

        return $this;
    }

    /**
     * @return ProviderSelectionInterface
     * @throws InvalidArgumentException
     *
     */
    public function getProviderSelection(): ProviderSelectionInterface
    {
        return $this->providerSelection ?? $this->make(UserSelectedProviderSelectionInterface::class);
    }

    /**
     * @return BankTransferPaymentMethodInterface
     */
    public function enablePaymentRetry(): BankTransferPaymentMethodInterface
    {
        $this->retry = new \stdClass();

        return $this;
    }

    public function isPaymentRetryEnabled(): bool
    {
        $retry = $this->retry ?? null;

        return (bool)$retry;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return PaymentMethods::BANK_TRANSFER;
    }
}
