<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Attributes\Field;
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;

final class PaymentExecuted extends _PaymentWithAuthorizationConfig implements PaymentExecutedInterface
{
    /**
     * @var \DateTimeInterface
     */
    #[Field]
    protected \DateTimeInterface $executedAt;

    /**
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface
    {
        return $this->executedAt;
    }
}
