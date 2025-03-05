<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\Payout\Beneficiary\BeneficiaryInterface;

interface PayoutRetrievedInterface extends HasAttributesInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getMerchantAccountId(): string;

    /**
     * @return int
     */
    public function getAmountInMinor(): int;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @return BeneficiaryInterface
     */
    public function getBeneficiary(): BeneficiaryInterface;

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return string|null
     */
    public function getSchemeId(): ?string;

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;
}
