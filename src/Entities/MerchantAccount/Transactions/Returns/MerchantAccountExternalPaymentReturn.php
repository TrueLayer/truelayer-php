<?php

declare(strict_types=1);

namespace TrueLayer\Entities\MerchantAccount\Transactions\Returns;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\MerchantAccount\Transactions\Returns\MerchantAccountExternalPaymentReturnInterface;

abstract class MerchantAccountExternalPaymentReturn extends Entity implements MerchantAccountExternalPaymentReturnInterface
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }
}
