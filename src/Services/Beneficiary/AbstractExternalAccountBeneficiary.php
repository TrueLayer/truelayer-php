<?php

declare(strict_types=1);

namespace TrueLayer\Services\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Traits\WithSdk;

abstract class AbstractExternalAccountBeneficiary implements BeneficiaryInterface
{
    use WithSdk, HasAttributes;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getNullableString('name');
    }

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function name(string $name = null): self
    {
        return $this->set('name', $name);
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->getNullableString('reference');
    }

    /**
     * @param string|null $reference
     *
     * @return $this
     */
    public function reference(string $reference = null): self
    {
        return $this->set('reference', $reference);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::EXTERNAL_ACCOUNT;
    }

    /**
     * @return string
     */
    abstract public function getSchemeType(): string;

    /**
     * @throws \TrueLayer\Exceptions\ValidationException
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return \array_merge_recursive($this->validate(), [
            'type' => $this->getType(),
            'scheme_identifier' => [
                'type' => $this->getSchemeType(),
            ],
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'reference' => 'required|string',
        ];
    }
}
