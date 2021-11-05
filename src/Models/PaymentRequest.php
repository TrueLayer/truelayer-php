<?php

declare(strict_types=1);

namespace TrueLayer\Models;

class PaymentRequest
{
    private int $amount;
    private string $currency;
    private Beneficiary $beneficiary;
    private User $user;

    public function __construct(int $amount, string $currency, Beneficiary $beneficiary, User $user)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->beneficiary = $beneficiary;
        $this->user = $user;
    }

    public function toArray(): array
    {
        return [
            'amount_in_minor' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => 'bank_transfer',
            'beneficiary' => $this->beneficiary->toArray(),
            'user' => $this->user->toArray(),
        ];
    }
}
