<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\Webhook\PayoutEventInterface;
use TrueLayer\Validation\ValidType;

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

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'beneficiary' => BeneficiaryInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return array_merge(parent::arrayFields(), [
            'payout_id',
            'beneficiary',
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'payout_id' => 'required|string',
            'beneficiary' => [ValidType::of(BeneficiaryInterface::class)],
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
}
