<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use Illuminate\Support\Carbon;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentSettledInterface extends PaymentRetrievedInterface
{
    /**
     * @return SourceOfFundsInterface
     */
    public function getSourceOfFunds(): SourceOfFundsInterface;

    /**
     * @return Carbon
     */
    public function getSettledAt(): Carbon;

    /**
     * @return Carbon
     */
    public function getExecutedAt(): Carbon;

    /**
     * @return ConfigurationInterface|null
     */
    public function getAuthorizationFlowConfig(): ?ConfigurationInterface;
}
