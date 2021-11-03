<?php

namespace TrueLayer\Contracts\Payments;

use Psr\Http\Message\UriInterface;

interface PaymentsApi
{
    public function createPayment();

    public function getPayment();

    public function createHostedPaymentPageLink(string $paymentId, string $resourceToken, UriInterface $returnUri): string;
}