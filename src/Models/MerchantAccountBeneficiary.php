<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Models\BeneficiaryInterface;

class MerchantAccountBeneficiary implements BeneficiaryInterface
{
    /**
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
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
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::MERCHANT_ACCOUNT;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
