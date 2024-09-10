<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Beneficiary;

use TrueLayer\Interfaces\ArrayableInterface;
use TrueLayer\Interfaces\HasAttributesInterface;

interface BeneficiaryInterface extends ArrayableInterface, HasAttributesInterface
{
    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string;

    /**
     * @param string $name
     * The name of the beneficiary.
     * For MerchantBeneficiary, if unspecified,
     * the API uses the account owner name associated with the selected merchant account.
     * Pattern: ^[^\(\)]+$
     *
     * @return $this
     */
    public function accountHolderName(string $name): self;

    /**
     * @return string|null
     */
    public function getReference(): ?string;

    /**
     * @param string $reference
     * A reference for the payment. Not visible to the remitter.
     * Pattern: ^[a-zA-Z0-9-:()\.,'\+ \?\/]+$
     *
     * @return $this
     */
    public function reference(string $reference): self;

    /**
     * @return string
     */
    public function getType(): string;
}
