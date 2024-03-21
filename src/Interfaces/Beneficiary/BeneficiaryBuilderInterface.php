<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Interfaces\MerchantAccount\MerchantAccountInterface;

interface BeneficiaryBuilderInterface
{
    /**
     * @return ExternalAccountBeneficiaryInterface
     */
    public function externalAccount(): ExternalAccountBeneficiaryInterface;

    /**
     * @param MerchantAccountInterface|null $merchantAccount
     *
     * @return MerchantBeneficiaryInterface
     * @throws InvalidArgumentException
     *
     */
    public function merchantAccount(MerchantAccountInterface $merchantAccount = null): MerchantBeneficiaryInterface;

    /**
     * @param mixed[] $data
     *
     * @return BeneficiaryInterface
     *
     * @throws InvalidArgumentException
     */
    public function fill(array $data): BeneficiaryInterface;
}
