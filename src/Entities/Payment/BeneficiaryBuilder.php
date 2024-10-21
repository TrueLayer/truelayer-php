<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Payment\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Payment\BeneficiaryInterface;
use TrueLayer\Interfaces\Payment\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Payment\MerchantBeneficiaryInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;

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
     * @param MerchantAccountInterface|null $merchantAccount
     *
     * @throws InvalidArgumentException
     *
     * @return MerchantBeneficiaryInterface
     */
    public function merchantAccount(?MerchantAccountInterface $merchantAccount = null): MerchantBeneficiaryInterface
    {
        $beneficiary = $this->entityFactory->make(MerchantBeneficiaryInterface::class);

        if ($merchantAccount && $merchantAccount->getId()) {
            $beneficiary->merchantAccountId($merchantAccount->getId());
        }

        return $beneficiary;
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
