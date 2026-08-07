<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;

/**
 * Builds a refund request. Refunds are only supported for settled merchant account payments.
 */
interface RefundRequestInterface extends HasAttributesInterface
{
    /**
     * The payment to refund, either its ID or a retrieved/created payment instance.
     *
     * @param string|PaymentRetrievedInterface|PaymentCreatedInterface $payment
     *
     * @return RefundRequestInterface
     */
    public function payment($payment): RefundRequestInterface;

    /**
     * A 'cent' value representing the refund amount, e.g. 100 = 1 GBP or 100 = 1 EUR.
     *
     * @param int $amount
     *
     * @return RefundRequestInterface
     */
    public function amountInMinor(int $amount): RefundRequestInterface;

    /**
     * A reference for the refund.
     *
     * @param string $reference
     *
     * @return RefundRequestInterface
     */
    public function reference(string $reference): RefundRequestInterface;

    /**
     * Optional custom key-value data to attach to the refund.
     *
     * @param array<string, string> $metadata
     *
     * @return RefundRequestInterface
     */
    public function metadata(array $metadata): RefundRequestInterface;

    /**
     * @param RequestOptionsInterface $requestOptions
     *
     * @return RefundRequestInterface
     */
    public function requestOptions(RequestOptionsInterface $requestOptions): RefundRequestInterface;

    /**
     * Send the request to create the refund.
     *
     * @return RefundCreatedInterface
     */
    public function create(): RefundCreatedInterface;
}
