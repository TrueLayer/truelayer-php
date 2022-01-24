<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Factories;

use TrueLayer\Interfaces\Api\MerchantAccountsApiInterface;
use TrueLayer\Interfaces\Api\PaymentsApiInterface;

interface ApiFactoryInterface
{
    /**
     * @return PaymentsApiInterface
     */
    public function paymentsApi(): PaymentsApiInterface;

    /**
     * @return MerchantAccountsApiInterface
     */
    public function merchantAccountsApi(): MerchantAccountsApiInterface;
}
