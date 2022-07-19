<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Webhook\Beneficiary;

interface BeneficiaryInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
