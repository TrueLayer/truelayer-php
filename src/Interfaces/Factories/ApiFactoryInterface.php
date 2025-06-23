<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Factories;

use TrueLayer\Interfaces\Api\MerchantAccountsApiInterface;
use TrueLayer\Interfaces\Api\PaymentsApiInterface;
use TrueLayer\Interfaces\Api\PayoutsApiInterface;
use TrueLayer\Interfaces\Api\ProvidersApiInterface;
use TrueLayer\Interfaces\Api\SignupPlusApiInterface;

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

    /**
     * @return PayoutsApiInterface
     */
    public function payoutsApi(): PayoutsApiInterface;

    /**
     * @return SignupPlusApiInterface
     */
    public function signupPlusApi(): SignupPlusApiInterface;

    /**
     * @return ProvidersApiInterface
     */
    public function providersApi(): ProvidersApiInterface;
}
