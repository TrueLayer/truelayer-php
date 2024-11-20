<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\SignupPlus;

use TrueLayer\Interfaces\HasAttributesInterface;

interface SignupPlusAccountDetailsInterface extends HasAttributesInterface
{
    /**
     * @return string
     */
    public function getAccountNumber(): string;

    /**
     * @return string
     */
    public function getSortCode(): string;

    /**
     * @return string
     */
    public function getIban(): string;

    /**
     * @return string
     */
    public function getProviderId(): string;
}
