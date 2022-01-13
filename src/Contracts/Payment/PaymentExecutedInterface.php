<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use Illuminate\Support\Carbon;
use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentExecutedInterface extends PaymentRetrievedInterface
{
    /**
     * @return Carbon
     */
    public function getExecutedAt(): Carbon;

    /**
     * @return ConfigurationInterface
     */
    public function getAuthorizationFlowConfig(): ConfigurationInterface;
}
