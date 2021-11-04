<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payments;

use Psr\Http\Message\UriInterface;
use TrueLayer\Models\PaymentRequest;

interface PaymentsApi
{
    public function createPayment(PaymentRequest $paymentRequest);

    public function getPayment();

    public function createHostedPaymentPageLink(string $paymentId, string $resourceToken, UriInterface $returnUri): string;
}
