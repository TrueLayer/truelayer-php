<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\PaymentRiskAssessmentInterface;

class PaymentRiskAssessment extends Entity implements PaymentRiskAssessmentInterface
{
    /**
     * @var string
     */
    protected string $segment;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'segment',
    ];

    /**
     * @return string|null
     */
    public function getSegment(): ?string
    {
        return $this->segment;
    }

    /**
     * @param string $segment
     *
     * @return PaymentRiskAssessmentInterface
     */
    public function segment(string $segment): PaymentRiskAssessmentInterface
    {
        $this->segment = $segment;

        return $this;
    }
}
