<?php

declare(strict_types=1);

namespace TrueLayer\Entities\MerchantAccount\Transactions;

use TrueLayer\Interfaces\MerchantAccount\Transactions\MerchantAccountExecutedPayoutInterface;

class MerchantAccountExecutedPayout extends MerchantAccountPayout implements MerchantAccountExecutedPayoutInterface
{
    /**
     * @var string
     */
    protected string $returnedBy;

    /**
     * @var string
     */
    protected string $schemeId;

    /**
     * @return string[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'returned_by',
            'scheme_id',
        ]);
    }

    /**
     * @return string|null
     */
    public function getReturnedBy(): ?string
    {
        return $this->returnedBy ?? null;
    }

    /**
     * @return string|null
     */
    public function getSchemeId(): ?string
    {
        return $this->schemeId ?? null;
    }
}
