<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SignupPlus;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\SignupPlus\SignupPlusAccountDetailsInterface;

class SignupPlusAccountDetails extends Entity implements SignupPlusAccountDetailsInterface
{
    /**
     * @var string
     */
    protected string $accountNumber;

    /**
     * @var string
     */
    protected string $sortCode;

    /**
     * @var string
     */
    protected string $iban;

    /**
     * @var string
     */
    protected string $providerId;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'account_number',
        'sort_code',
        'iban',
        'provider_id',
    ];

    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber ?? null;
    }

    /**
     * @return string|null
     */
    public function getSortCode(): ?string
    {
        return $this->sortCode ?? null;
    }

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban ?? null;
    }

    /**
     * @return string
     */
    public function getProviderId(): string
    {
        return $this->providerId;
    }
}
