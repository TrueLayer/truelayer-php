<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Beneficiary;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\MerchantBeneficiaryInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;

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
     * @param MerchantAccountInterface|null $merchantAccount
     *
     * @return MerchantBeneficiaryInterface
     *
     * @throws InvalidArgumentException
     */
    public function merchantAccount(MerchantAccountInterface $merchantAccount = null): MerchantBeneficiaryInterface
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
     * @return BeneficiaryInterface
     *
     * @throws InvalidArgumentException
     */
    public function fill(array $data): BeneficiaryInterface
    {
        return $this->entityFactory->make(BeneficiaryInterface::class, $data);
    }
}
