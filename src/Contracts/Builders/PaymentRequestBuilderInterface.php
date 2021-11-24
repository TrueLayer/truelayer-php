<?php

namespace TrueLayer\Contracts\Builders;

use TrueLayer\Contracts\Models\PaymentInterface;

interface PaymentRequestBuilderInterface extends PaymentInterface
{
    public function create();
}
