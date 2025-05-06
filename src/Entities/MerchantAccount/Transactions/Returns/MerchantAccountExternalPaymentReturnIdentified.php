<?php

declare(strict_types=1);

namespace TrueLayer\Entities\MerchantAccount\Transactions\Returns;

use TrueLayer\Interfaces\MerchantAccount\Transactions\Returns\MerchantAccountExternalPaymentReturnIdentifiedInterface;

class MerchantAccountExternalPaymentReturnIdentified extends MerchantAccountExternalPaymentReturn implements MerchantAccountExternalPaymentReturnIdentifiedInterface
{
    /**
     * @var string
     */
    protected string $returnId;

    /**
     * @return string
     */
    public function getReturnId(): string
    {
        return $this->returnId;
    }
}
