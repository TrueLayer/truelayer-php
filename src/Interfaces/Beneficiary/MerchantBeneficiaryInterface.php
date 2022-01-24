<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

interface MerchantBeneficiaryInterface extends BeneficiaryInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @param string $id
     *
     * @return MerchantBeneficiaryInterface
     */
    public function id(string $id): MerchantBeneficiaryInterface;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $name
     *
     * @return MerchantBeneficiaryInterface
     */
    public function name(string $name): MerchantBeneficiaryInterface;

    /**
     * @return string
     */
    public function getType(): string;
}
