<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payout\BusinessAccountBeneficiaryInterface;

final class BusinessAccountBeneficiary extends Entity implements BusinessAccountBeneficiaryInterface
{
    /**
     * @var string
     */
    protected string $reference;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'reference',
        'type',
    ];

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference ?? null;
    }

    /**
     * @param string $reference
     *
     * @return BusinessAccountBeneficiaryInterface
     */
    public function reference(string $reference): BusinessAccountBeneficiaryInterface
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::BUSINESS_ACCOUNT;
    }
}
