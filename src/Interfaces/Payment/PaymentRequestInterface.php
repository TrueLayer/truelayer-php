<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\PaymentMethod\PaymentMethodInterface;
use TrueLayer\Interfaces\RequestOptionsInterface;
use TrueLayer\Interfaces\UserInterface;

/**
 * Builds a payment creation request.
 */
interface PaymentRequestInterface extends HasAttributesInterface
{
    /**
     * A 'cent' value representing the amount, e.g. 100 = 1 GBP or 100 = 1 EUR.
     *
     * @param int $amount
     *
     * @return PaymentRequestInterface
     */
    public function amountInMinor(int $amount): PaymentRequestInterface;

    /**
     * The three-letter ISO 4217 currency code the payment should be made in.
     *
     * @param string $currency
     *
     * @return PaymentRequestInterface
     */
    public function currency(string $currency): PaymentRequestInterface;

    /**
     * Optional custom key-value data to attach to the payment. Limited to 10 pairs, each key up to 40
     * characters and each value up to 500 characters.
     *
     * @param array<string, string> $metadata
     *
     * @return PaymentRequestInterface
     */
    public function metadata(array $metadata): PaymentRequestInterface;

    /**
     * The payment method to use, such as a bank transfer.
     *
     * @param PaymentMethodInterface $paymentMethod
     *
     * @return PaymentRequestInterface
     */
    public function paymentMethod(PaymentMethodInterface $paymentMethod): PaymentRequestInterface;

    /**
     * Optional configuration for risk assessment and the `payment_creditable` webhook. Contact TrueLayer
     * before sending this field.
     *
     * @param PaymentRiskAssessmentInterface|null $riskAssessment
     *
     * @return PaymentRiskAssessmentInterface
     */
    public function riskAssessment(?PaymentRiskAssessmentInterface $riskAssessment): PaymentRiskAssessmentInterface;

    /**
     * Details of the end user making the payment. Not required for payments made over a mandate, or when
     * using your own PISP licence.
     *
     * @param UserInterface $user
     *
     * @return PaymentRequestInterface
     */
    public function user(UserInterface $user): PaymentRequestInterface;

    /**
     * Requests a hosted page URI as part of payment creation, so you don't have to build the Hosted
     * Payments Page URL yourself afterwards. Cannot be combined with starting the authorization flow
     * explicitly.
     *
     * @param PaymentHostedPageInterface|null $hostedPage
     *
     * @return PaymentHostedPageInterface
     */
    public function hostedPage(?PaymentHostedPageInterface $hostedPage = null): PaymentHostedPageInterface;

    /**
     * @param RequestOptionsInterface $requestOptions
     *
     * @return PaymentRequestInterface
     */
    public function requestOptions(RequestOptionsInterface $requestOptions): PaymentRequestInterface;

    /**
     * Send the request to create the payment.
     *
     * @return PaymentCreatedInterface
     */
    public function create(): PaymentCreatedInterface;
}
