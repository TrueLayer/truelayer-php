<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

/**
 * Optional configuration for risk assessment and the `payment_creditable` webhook.
 */
interface PaymentRiskAssessmentInterface
{
    /**
     * @return string|null
     */
    public function getSegment(): ?string;

    /**
     * The risk segment of this payment. Contact TrueLayer before sending this field.
     *
     * @param string $segment
     *
     * @return PaymentRiskAssessmentInterface
     */
    public function segment(string $segment): PaymentRiskAssessmentInterface;
}
