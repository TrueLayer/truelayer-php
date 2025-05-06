<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\MerchantAccount\Transactions;

interface MerchantAccountExecutedPayoutInterface extends MerchantAccountPayoutInterface
{
    /**
     * @return string|null
     */
    public function getReturnedBy(): ?string;

    /**
     * @return string|null
     */
    public function getSchemeId(): ?string;
}
