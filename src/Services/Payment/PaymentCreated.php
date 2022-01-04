<?php

declare(strict_types=1);

namespace TrueLayer\Services\Payment;

use TrueLayer\Contracts\Hpp\HppHelperInterface;
use TrueLayer\Contracts\Payment\PaymentCreatedInterface;
use TrueLayer\Contracts\Payment\PaymentRetrievedInterface;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Traits\WithSdk;

final class PaymentCreated implements PaymentCreatedInterface
{
    use WithSdk, HasAttributes;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->get('id');
    }

    /**
     * @return string
     */
    public function getResourceToken(): string
    {
        return $this->get('resource_token');
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->get('user.id');
    }

    /**
     * @return HppHelperInterface
     */
    public function hostedPaymentsPage(): HppHelperInterface
    {
        return $this->getSdk()
            ->hostedPaymentsPage()
            ->paymentId($this->getId())
            ->resourceToken($this->getResourceToken());
    }

    /**
     * @throws \TrueLayer\Exceptions\ApiRequestJsonSerializationException
     * @throws \TrueLayer\Exceptions\ApiRequestValidationException
     * @throws \TrueLayer\Exceptions\ApiResponseUnsuccessfulException
     * @throws \TrueLayer\Exceptions\ApiResponseValidationException
     *
     * @return PaymentRetrievedInterface
     */
    public function getDetails(): PaymentRetrievedInterface
    {
        return $this->getSdk()->getPaymentDetails(
            $this->getId()
        );
    }
}
