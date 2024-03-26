<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;

final class PaymentExecuted extends _PaymentWithAuthorizationConfig implements PaymentExecutedInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $executedAt;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'executed_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'executed_at',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface
    {
        return $this->executedAt;
    }
}
