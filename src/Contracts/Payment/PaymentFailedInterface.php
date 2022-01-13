<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use Illuminate\Support\Carbon;
use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentFailedInterface extends ArrayableInterface
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
     * @return string
     */
    public function getFailureReason(): string;

    /**
     * @return ConfigurationInterface
     */
    public function getAuthorizationFlowConfig(): ConfigurationInterface;
}
