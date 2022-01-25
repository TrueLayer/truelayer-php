<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment;

use TrueLayer\Interfaces\Beneficiary\BeneficiaryInterface;
use TrueLayer\Interfaces\HasAttributesInterface;
use TrueLayer\Interfaces\UserInterface;

interface PaymentRequestInterface extends HasAttributesInterface
{
    /**
     * @param int $amount
     *
     * @return PaymentRequestInterface
     */
    public function amountInMinor(int $amount): PaymentRequestInterface;

    /**
     * @param string $currency
     *
     * @return PaymentRequestInterface
     */
    public function currency(string $currency): PaymentRequestInterface;

    /**
     * @param PaymentMethodInterface $paymentMethod
     *
     * @return PaymentRequestInterface
     */
    public function paymentMethod(PaymentMethodInterface $paymentMethod): PaymentRequestInterface;

    /**
     * @param BeneficiaryInterface $beneficiary
     *
     * @return PaymentRequestInterface
     */
    public function beneficiary(BeneficiaryInterface $beneficiary): PaymentRequestInterface;

    /**
     * @param UserInterface $user
     *
     * @return PaymentRequestInterface
     */
    public function user(UserInterface $user): PaymentRequestInterface;

    /**
     * @return PaymentCreatedInterface
     */
    public function create(): PaymentCreatedInterface;
}
