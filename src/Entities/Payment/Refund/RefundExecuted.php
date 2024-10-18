<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Refund;

use TrueLayer\Attributes\Field;
use TrueLayer\Interfaces\Payment\RefundExecutedInterface;

final class RefundExecuted extends RefundRetrieved implements RefundExecutedInterface
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
