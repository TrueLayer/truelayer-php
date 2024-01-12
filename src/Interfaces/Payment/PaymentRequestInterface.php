<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Interfaces\UserInterface;

interface PaymentRequestInterface extends HasAttributesInterface
{
    /**
     * @param int $amount
     *
     * @return PaymentRequestInterface
     */
    public function amountInMinor(int $amount): PaymentRequestInterface;

    /**
     * @param string $currency
     *
     * @return PaymentRequestInterface
     */
    public function currency(string $currency): PaymentRequestInterface;

    /**
     * @param array<string, string> $metadata
     *
     * @return PaymentRequestInterface
     */
    public function metadata(array $metadata): PaymentRequestInterface;

    /**
     * @param PaymentMethodInterface $paymentMethod
     *
     * @return PaymentRequestInterface
     */
    public function paymentMethod(PaymentMethodInterface $paymentMethod): PaymentRequestInterface;

    /**
     * @param UserInterface $user
     *
     * @return PaymentRequestInterface
     */
    public function user(UserInterface $user): PaymentRequestInterface;

    /**
     * @param RequestOptionsInterface $requestOptions
     *
     * @return PaymentRequestInterface
     */
    public function requestOptions(RequestOptionsInterface $requestOptions): PaymentRequestInterface;

    /**
     * @return PaymentCreatedInterface
     */
    public function create(): PaymentCreatedInterface;
}
