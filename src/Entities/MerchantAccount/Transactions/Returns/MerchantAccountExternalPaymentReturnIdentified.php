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
     * @return string[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'return_id',
        ]);
    }

    /**
     * @return string
     */
    public function getReturnId(): string
    {
        return $this->returnId;
    }
}
