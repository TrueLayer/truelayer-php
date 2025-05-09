<?php

declare(strict_types=1);

namespace TrueLayer\Entities\MerchantAccount\Transactions;

use TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountExternalPaymentInterface;
use TrueLayer\Interfaces\MerchantAccount\Transactions\Returns\MerchantAccountExternalPaymentReturnInterface;

class MerchantAccountExternalPayment extends MerchantAccountTransactionRetrieved implements MerchantAccountExternalPaymentInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $settledAt;

    /**
     * @var MerchantAccountExternalPaymentReturnInterface
     */
    protected MerchantAccountExternalPaymentReturnInterface $returnedFor;

    /**
     * @var array|string[]
     */
    protected array $casts = [
        'settled_at' => \DateTimeInterface::class,
    ];

    /**
     * @return string[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'settled_at',
            'returned_for',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSettledAt(): \DateTimeInterface
    {
        return $this->settledAt;
    }

    /**
     * @return MerchantAccountExternalPaymentReturnInterface|null
     */
    public function getRefundFor(): ?MerchantAccountExternalPaymentReturnInterface
    {
        return $this->returnedFor ?? null;
    }
}
