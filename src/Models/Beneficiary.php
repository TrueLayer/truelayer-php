<?php

namespace TrueLayer\Models;

class Beneficiary
{
    private string $type;
    private string $reference;
    private string $name;
    private string $sortCode;
    private string $accountNumber;

    public function __construct(string $type, string $reference, string $name, string $sortCode, string $accountNumber)
    {
        $this->type = $type;
        $this->reference = $reference;
        $this->name = $name;
        $this->sortCode = $sortCode;
        $this->accountNumber = $accountNumber;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'reference' => $this->reference,
            'name' => $this->name,
            'scheme_identifier' => [
                'type' => 'sort_code_account_number',
                'sort_code' => $this->sortCode,
                'account_number' => $this->accountNumber,
            ]
        ];
    }
}
