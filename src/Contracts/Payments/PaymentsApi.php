<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payments;

use Psr\Http\Message\UriInterface;
use TrueLayer\Models\PaymentRequest;
use TrueLayer\Models\PaymentResponse;

interface PaymentsApi
{
    public function createPayment(PaymentRequest $paymentRequest);

    public function getPayment(string $paymentId): PaymentResponse;

    public function createHostedPaymentPageLink(string $paymentId, string $resourceToken, UriInterface $returnUri): string;
}
