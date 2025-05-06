<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\MerchantAccount\Transactions;

use TrueLayer\Interfaces\MerchantAccount\Transactions\Returns\MerchantAccountExternalPaymentReturnInterface;

interface MerchantAccountExternalPaymentInterface extends MerchantAccountTransactionRetrievedInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getSettledAt(): \DateTimeInterface;

    /**
     * @return MerchantAccountExternalPaymentReturnInterface|null
     */
    public function getRefundFor(): ?MerchantAccountExternalPaymentReturnInterface;
}
