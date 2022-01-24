<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Beneficiary;

use TrueLayer\Interfaces\Beneficiary\BeneficiaryBuilderInterface;
use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\MerchantBeneficiaryInterface;
use TrueLayer\Interfaces\Beneficiary\ScanBeneficiaryInterface;
use TrueLayer\Interfaces\Factories\EntityFactoryInterface;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;

final class BeneficiaryBuilder implements BeneficiaryBuilderInterface
{
    /**
     * @var EntityFactoryInterface
     */
    private EntityFactoryInterface $entityFactory;

    /**
     * @param EntityFactoryInterface $entityFactory
     */
    public function __construct(EntityFactoryInterface $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    /**
     * @return ScanBeneficiaryInterface
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function sortCodeAccountNumber(): ScanBeneficiaryInterface
    {
        return $this->entityFactory->make(ScanBeneficiaryInterface::class);
    }

    /**
     * @return IbanBeneficiary
     */
//    public function ibanAccount(): IbanBeneficiary
//    {
//        return IbanBeneficiary::make($this->getValidatorFactory());
//    }

    /**
     * @param MerchantAccountInterface|null $merchantAccount
     * @return MerchantBeneficiaryInterface
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function merchantAccount(MerchantAccountInterface $merchantAccount = null): MerchantBeneficiaryInterface
    {
        $beneficiary = $this->entityFactory->make(MerchantBeneficiaryInterface::class);

        if ($merchantAccount) {
            $beneficiary->id($merchantAccount->getId());
        }

        return $beneficiary;
    }

    /**
     * @param array $data
     * @return BeneficiaryInterface
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function fill(array $data): BeneficiaryInterface
    {
        return $this->entityFactory->make(BeneficiaryInterface::class, $data);
    }
}
