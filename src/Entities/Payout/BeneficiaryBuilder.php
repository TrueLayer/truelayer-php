<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Payout\PaymentSourceBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\PayoutBeneficiaryInterface;

final class BeneficiaryBuilder extends EntityBuilder implements BeneficiaryBuilderInterface
{
    /**
     * @return ExternalAccountBeneficiaryInterface
     *
     * @throws InvalidArgumentException
     */
    public function externalAccount(): ExternalAccountBeneficiaryInterface
    {
        return $this->entityFactory->make(ExternalAccountBeneficiaryInterface::class);
    }

    /**
     * @return PaymentSourceBeneficiaryInterface
     * @throws InvalidArgumentException
     */
    public function paymentSource(): PaymentSourceBeneficiaryInterface
    {
        return $this->entityFactory->make(PaymentSourceBeneficiaryInterface::class);
    }

    /**
     * @param mixed[] $data
     *
     * @return PayoutBeneficiaryInterface
     *
     * @throws InvalidArgumentException
     */
    public function fill(array $data): PayoutBeneficiaryInterface
    {
        return $this->entityFactory->make(PayoutBeneficiaryInterface::class, $data);
    }
}
