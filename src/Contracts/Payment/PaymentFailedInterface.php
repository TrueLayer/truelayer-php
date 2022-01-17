<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use Illuminate\Support\Carbon;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentFailedInterface extends PaymentRetrievedInterface
{
    /**
     * @return Carbon
     */
    public function getFailedAt(): Carbon;

    /**
     * @return string
     */
    public function getFailureStage(): string;

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string;

    /**
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;
}
