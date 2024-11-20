<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\Beneficiary;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Payout\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\BusinessAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\PaymentSourceBeneficiaryInterface;

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
     * @return BeneficiaryInterface
     */
    public function fill(array $data): BeneficiaryInterface
    {
        return $this->entityFactory->make(BeneficiaryInterface::class, $data);
    }
}
