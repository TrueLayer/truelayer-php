<?php

declare(strict_types=1);

namespace TrueLayer\Services\Beneficiary;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Beneficiary\BeneficiaryInterface;
use TrueLayer\Contracts\Sdk\SdkInterface;
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
        return $this->get('name');
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
        return $this->get('reference');
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
     * @return array
     */
    public function toArray(): array
    {
        return \array_merge_recursive($this->data, [
            'type' => $this->getType(),
            'scheme_identifier' => [
                'type' => $this->getSchemeType(),
            ],
        ]);
    }
}
