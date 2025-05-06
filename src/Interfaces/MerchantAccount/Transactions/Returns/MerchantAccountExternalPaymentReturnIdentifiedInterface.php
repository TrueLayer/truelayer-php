<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\MerchantAccount\Transactions\Returns;

interface MerchantAccountExternalPaymentReturnIdentifiedInterface extends MerchantAccountExternalPaymentReturnInterface
{
    /**
     * @return string
     */
    public function getReturnId(): string;
}
