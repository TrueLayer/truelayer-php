<?php

declare(strict_types=1);

namespace TrueLayer\Services\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Traits\WithSdk;

final class MerchantAccountBeneficiary implements BeneficiaryInterface
{
    use WithSdk, HasAttributes;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->get('id');
    }

    /**
     * @param string|null $id
     *
     * @return MerchantAccountBeneficiary
     */
    public function id(string $id = null): MerchantAccountBeneficiary
    {
        return $this->set('id', $id);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->get('name');
    }

    /**
     * @param string|null $name
     *
     * @return MerchantAccountBeneficiary
     */
    public function name(string $name = null): MerchantAccountBeneficiary
    {
        return $this->set('name', $name);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::MERCHANT_ACCOUNT;
    }

    /**
     * @return mixed[]
     * @throws \TrueLayer\Exceptions\ValidationException
     */
    public function toArray(): array
    {
        return \array_merge($this->validate(), [
            'type' => $this->getType(),
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'id' => 'required|string',
            'name' => 'required|string'
        ];
    }
}
