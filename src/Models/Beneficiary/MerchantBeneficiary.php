<?php

declare(strict_types=1);

namespace TrueLayer\Models\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Models\Model;

final class MerchantBeneficiary extends Model implements BeneficiaryInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'name',
        'type',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'id' => 'required|string',
        'name' => 'nullable|string',
    ];

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * @param string $id
     *
     * @return MerchantBeneficiary
     */
    public function id(string $id): MerchantBeneficiary
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @param string $name
     *
     * @return MerchantBeneficiary
     */
    public function name(string $name): MerchantBeneficiary
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
}
