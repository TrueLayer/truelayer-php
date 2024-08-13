<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Payout\BusinessAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\PaymentSourceBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\PayoutBeneficiaryInterface;

final class BeneficiaryBuilder extends EntityBuilder implements BeneficiaryBuilderInterface
{
    /**
     * @throws InvalidArgumentException
     *
     * @return ExternalAccountBeneficiaryInterface
     */
    public function externalAccount(): ExternalAccountBeneficiaryInterface
    {
        return $this->entityFactory->make(ExternalAccountBeneficiaryInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return PaymentSourceBeneficiaryInterface
     */
    public function paymentSource(): PaymentSourceBeneficiaryInterface
    {
        return $this->entityFactory->make(PaymentSourceBeneficiaryInterface::class);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return BusinessAccountBeneficiaryInterface
     */
    public function businessAccount(): BusinessAccountBeneficiaryInterface
    {
        return $this->entityFactory->make(BusinessAccountBeneficiaryInterface::class);
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     *
     * @return PayoutBeneficiaryInterface
     */
    public function fill(array $data): PayoutBeneficiaryInterface
    {
        return $this->entityFactory->make(PayoutBeneficiaryInterface::class, $data);
    }
}
