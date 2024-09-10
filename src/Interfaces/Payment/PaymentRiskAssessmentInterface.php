<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

interface PaymentRiskAssessmentInterface
{
    /**
     * @return string|null
     */
    public function getSegment(): ?string;

    /**
     * @param string $segment
     * The risk segment of this payment. Please contact TrueLayer before sending this field.
     * Pattern: ^[^\(\)]+$
     *
     * @return PaymentRiskAssessmentInterface
     */
    public function segment(string $segment): PaymentRiskAssessmentInterface;
}
