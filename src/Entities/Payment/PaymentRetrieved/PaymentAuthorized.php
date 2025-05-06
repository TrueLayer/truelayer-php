<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Interfaces\Payment\PaymentAuthorizedInterface;

final class PaymentAuthorized extends _PaymentWithAuthorizationConfig implements PaymentAuthorizedInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $creditableAt;

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
     * @return \DateTimeInterface
     */
    public function getCreditableAt(): \DateTimeInterface
    {
        return $this->creditableAt;
    }
}
