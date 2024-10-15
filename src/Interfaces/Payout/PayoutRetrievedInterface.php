<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout;

use TrueLayer\Interfaces\HasAttributesInterface;

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
     * @return PayoutBeneficiaryInterface
     */
    public function getBeneficiary(): PayoutBeneficiaryInterface;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array;

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;
}
