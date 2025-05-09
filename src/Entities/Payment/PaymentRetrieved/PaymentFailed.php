<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\PaymentFailedInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;

final class PaymentFailed extends PaymentFailure implements PaymentFailedInterface
{
    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'creditable_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'creditable_at',
        ]);
    }

    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $creditableAt;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreditableAt(): ?\DateTimeInterface
    {
        return $this->creditableAt ?? null;
    }
}
