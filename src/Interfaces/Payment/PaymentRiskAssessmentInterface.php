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
     *
     * @return PaymentRiskAssessmentInterface
     */
    public function segment(string $segment): PaymentRiskAssessmentInterface;
}
