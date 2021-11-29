<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Models\BeneficiaryInterface;

abstract class AbstractExternalAccountBeneficiary implements BeneficiaryInterface
{
    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $reference = null;

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
     * @return $this
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     *
     * @return $this
     */
    public function reference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
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
     * @param array $schemeIdentifier
     *
     * @return array[]
     */
    protected function wrap(array $schemeIdentifier): array
    {
        return [
            'type' => $this->getType(),
            'reference' => $this->getReference(),
            'name' => $this->getName(),
            'scheme_identifier' => $schemeIdentifier,
        ];
    }
}
