<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Payout\PaymentSourceBeneficiaryInterface;
use TrueLayer\Interfaces\Payout\PayoutBeneficiaryInterface;

final class BeneficiaryBuilder extends EntityBuilder implements BeneficiaryBuilderInterface
{
    /**
     * @return ExternalAccountBeneficiaryInterface
     * @throws InvalidArgumentException
     *
     * @throws ValidationException
     */
    public function externalAccount(): ExternalAccountBeneficiaryInterface
    {
        return $this->entityFactory->make(ExternalAccountBeneficiaryInterface::class);
    }

    /**
     * @return PaymentSourceBeneficiaryInterface
     * @throws ValidationException
     *
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
     * @throws InvalidArgumentException
     *
     * @throws ValidationException
     */
    public function fill(array $data): PayoutBeneficiaryInterface
    {
        return $this->entityFactory->make(PayoutBeneficiaryInterface::class, $data);
    }
}
