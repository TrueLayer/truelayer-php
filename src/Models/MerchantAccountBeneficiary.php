<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Models\BeneficiaryInterface;

class MerchantAccountBeneficiary implements BeneficiaryInterface
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @param string $id
     *
     * @return MerchantAccountBeneficiary
     */
    public function id(string $id): MerchantAccountBeneficiary
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return MerchantAccountBeneficiary
     */
    public function name(string $name): MerchantAccountBeneficiary
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => BeneficiaryTypes::MERCHANT_ACCOUNT,
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
