<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook\Beneficiary;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Webhook\Beneficiary\BeneficiaryInterface;

class Beneficiary extends Entity implements BeneficiaryInterface
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @return string[]
     */
    protected array $arrayFields = [
        'type',
    ];
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
