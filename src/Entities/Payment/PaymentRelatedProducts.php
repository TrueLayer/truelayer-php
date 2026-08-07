<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\PaymentRelatedProductsInterface;

final class PaymentRelatedProducts extends Entity implements PaymentRelatedProductsInterface
{
    /**
     * @var \stdClass|null
     */
    protected ?\stdClass $signupPlus;

    /**
     * @var string[]
     */
    protected array $casts = [
        'signup_plus' => \stdClass::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'signup_plus',
    ];

    /**
     * @return PaymentRelatedProductsInterface
     */
    public function signupPlus(): PaymentRelatedProductsInterface
    {
        $this->signupPlus = new \stdClass();

        return $this;
    }

    /**
     * @return bool
     */
    public function isSignupPlusEnabled(): bool
    {
        return (bool) ($this->signupPlus ?? null);
    }
}
