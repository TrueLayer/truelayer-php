<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SignupPlus;

use TrueLayer\Interfaces\HasAttributesInterface;

interface SignupPlusAccountDetailsInterface extends HasAttributesInterface
{
    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string;

    /**
     * @return string|null
     */
    public function getSortCode(): ?string;

    /**
     * @return string|null
     */
    public function getIban(): ?string;

    /**
     * @return string
     */
    public function getProviderId(): string;
}
