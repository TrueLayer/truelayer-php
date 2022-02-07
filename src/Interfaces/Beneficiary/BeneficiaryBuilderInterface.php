<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
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
     * @throws InvalidArgumentException
     *
     * @return MerchantBeneficiaryInterface
     */
    public function merchantAccount(MerchantAccountInterface $merchantAccount = null): MerchantBeneficiaryInterface;

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return BeneficiaryInterface
     */
    public function fill(array $data): BeneficiaryInterface;
}
