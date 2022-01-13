<?php

declare(strict_types=1);

namespace TrueLayer\Contracts\Payment;

use TrueLayer\Contracts\ArrayableInterface;
use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;

interface PaymentAuthorizedInterface extends PaymentRetrievedInterface
{
    /**
     * @return ConfigurationInterface
     */
    public function getAuthorizationFlowConfig(): ConfigurationInterface;

}
