<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payout\Beneficiary;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface BeneficiaryInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string
     */
    public function getReference(): string;

    /**
     * @param string $reference
     *
     * @return $this
     */
    public function reference(string $reference): self;

    /**
     * @return string
     */
    public function getType(): string;
}
