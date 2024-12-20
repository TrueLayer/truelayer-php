<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Webhook\PayoutEventInterface;

class PayoutEvent extends Event implements PayoutEventInterface
{
    /**
     * @var string
     */
    protected string $payoutId;

    /**
     * @var BeneficiaryInterface
     */
    protected BeneficiaryInterface $beneficiary;

    /**
     * @var array<string, string>
     */
    protected array $metadata;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'beneficiary' => BeneficiaryInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'payout_id',
            'beneficiary',
            'metadata',
        ]);
    }

    /**
     * @return string
     */
    public function getPayoutId(): string
    {
        return $this->payoutId;
    }

    /**
     * @return BeneficiaryInterface|null
     */
    public function getBeneficiary(): ?BeneficiaryInterface
    {
        return $this->beneficiary ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->metadata ?? [];
    }
}
