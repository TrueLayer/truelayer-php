<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;

interface RefundRequestInterface extends HasAttributesInterface
{
    /**
     * @param string|PaymentRetrievedInterface|PaymentCreatedInterface $payment
     *
     * @return RefundRequestInterface
     */
    public function payment($payment): RefundRequestInterface;

    /**
     * @param int $amount
     *
     * @return RefundRequestInterface
     */
    public function amountInMinor(int $amount): RefundRequestInterface;

    /**
     * @param string $reference
     *
     * @return RefundRequestInterface
     */
    public function reference(string $reference): RefundRequestInterface;

    /**
     * @param RequestOptionsInterface $requestOptions
     *
     * @return RefundRequestInterface
     */
    public function requestOptions(RequestOptionsInterface $requestOptions): RefundRequestInterface;

    /**
     * @return RefundCreatedInterface
     */
    public function create(): RefundCreatedInterface;
}
