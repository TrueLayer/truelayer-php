<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Beneficiary;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\HasAttributesInterface;

interface BeneficiaryInterface extends ArrayableInterface, HasAttributesInterface
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
