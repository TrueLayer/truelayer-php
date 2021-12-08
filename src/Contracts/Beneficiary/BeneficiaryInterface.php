<?php

namespace TrueLayer\Contracts\Beneficiary;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\ArrayFillableInterface;

interface BeneficiaryInterface extends ArrayableInterface, ArrayFillableInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): self;

    /**
     * @return string
     */
    public function getType(): string;
}
