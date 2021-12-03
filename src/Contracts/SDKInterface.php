<?php

namespace TrueLayer\Contracts;

use TrueLayer\Contracts\Builders\BeneficiaryBuilderInterface;
use TrueLayer\Contracts\Models\PaymentCreatedInterface;
use TrueLayer\Contracts\Models\PaymentInterface;
use TrueLayer\Contracts\Models\UserInterface;

interface SDKInterface
{
    /**
     * @param array $data
     *
     * @return UserInterface
     */
    public function user(array $data = []): UserInterface;

    /**
     * @return BeneficiaryBuilderInterface
     */
    public function beneficiary(): BeneficiaryBuilderInterface;

    /**
     * @param array $data
     *
     * @return PaymentInterface
     */
    public function payment(array $data = []): PaymentInterface;

    /**
     * @param PaymentInterface $payment
     *
     * @return PaymentCreatedInterface
     */
    public function createPayment(PaymentInterface $payment): PaymentCreatedInterface;

    /**
     * @param string $id
     *
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     *
     * @return PaymentInterface
     */
    public function getPayment(string $id): PaymentInterface;
}
