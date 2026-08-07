<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\ArrayableInterface;

interface PaymentRelatedProductsInterface extends ArrayableInterface
{
    /**
     * Enable the Signup+ product on the payment.
     *
     * @return PaymentRelatedProductsInterface
     */
    public function signupPlus(): PaymentRelatedProductsInterface;

    /**
     * @return bool
     */
    public function isSignupPlusEnabled(): bool;
}
