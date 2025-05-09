<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\MerchantAccount\Transactions\Returns;

interface MerchantAccountExternalPaymentReturnInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
