<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Models\BeneficiaryInterface;

abstract class AbstractExternalAccountBeneficiary implements BeneficiaryInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $reference;

    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function reference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @param array $schemeIdentifier
     * @return array[]
     */
    protected function wrap(array $schemeIdentifier): array
    {
        return  [
            'type' => BeneficiaryTypes::EXTERNAL_ACCOUNT,
            'reference' => $this->reference,
            'name' => $this->name,
            'scheme_identifier' => $schemeIdentifier,
        ];
    }
}




















