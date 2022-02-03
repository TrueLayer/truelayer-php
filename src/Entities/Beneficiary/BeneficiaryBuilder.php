<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Beneficiary;

use TrueLayer\Entities\EntityBuilder;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\ExternalAccountBeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\MerchantBeneficiaryInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;

final class BeneficiaryBuilder extends EntityBuilder implements BeneficiaryBuilderInterface
{
    /**
     * @return ExternalAccountBeneficiaryInterface
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function externalAccount(): ExternalAccountBeneficiaryInterface
    {
        return $this->entityFactory->make(ExternalAccountBeneficiaryInterface::class);
    }

    /**
     * @param MerchantAccountInterface|null $merchantAccount
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return MerchantBeneficiaryInterface
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
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return BeneficiaryInterface
     */
    public function fill(array $data): BeneficiaryInterface
    {
        return $this->entityFactory->make(BeneficiaryInterface::class, $data);
    }
}
