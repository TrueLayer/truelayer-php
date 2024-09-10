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
     * A 'cent' value representing the amount. eg 100 == 1GBP or 100 = 1EUR
     *
     * @return PaymentRequestInterface
     */
    public function amountInMinor(int $amount): PaymentRequestInterface;

    /**
     * @param string $currency
     * @see \TrueLayer\Constants\Currencies
     *
     * @return PaymentRequestInterface
     */
    public function currency(string $currency): PaymentRequestInterface;

    /**
     * @param array<string, string> $metadata
     * Optional field for adding custom key-value data to a resource.
     * This object can contain a maximum of 10 key-value pairs,
     * each with a key with a maximum length of 40 characters
     * and a non-null value with a maximum length of 500 characters.
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
     * @param PaymentRiskAssessmentInterface|null $riskAssessment
     * An optional field for configuring risk assessment and the payment_creditable webhook.
     *
     * @see https://docs.truelayer.com/docs/settlement-risk-and-the-payment_creditable-webhook
     * Learn how to enable this field
     *
     * @return PaymentRiskAssessmentInterface
     */
    public function riskAssessment(?PaymentRiskAssessmentInterface $riskAssessment): PaymentRiskAssessmentInterface;

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
