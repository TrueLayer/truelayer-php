<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Attributes\Field;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Payment\RefundRequestInterface;
use TrueLayer\Interfaces\Payment\RefundRetrievedInterface;
use TrueLayer\Traits\ProvidesApiFactory;

final class PaymentSettled extends _PaymentWithAuthorizationConfig implements PaymentSettledInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var PaymentSourceInterface
     */
    #[Field]
    protected PaymentSourceInterface $paymentSource;

    /**
     * @var \DateTimeInterface
     */
    #[Field]
    protected \DateTimeInterface $executedAt;

    /**
     * @var \DateTimeInterface
     */
    #[Field]
    protected \DateTimeInterface $settledAt;

    /**
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface
    {
        return $this->executedAt;
    }

    /**
     * @return PaymentSourceInterface
     */
    public function getPaymentSource(): PaymentSourceInterface
    {
        return $this->paymentSource;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSettledAt(): \DateTimeInterface
    {
        return $this->settledAt;
    }

    /**
     * @return RefundRequestInterface
     * @throws InvalidArgumentException
     *
     */
    public function refund(): RefundRequestInterface
    {
        return $this->make(RefundRequestInterface::class)
            ->payment($this->getId());
    }

    /**
     * @param string $refundId
     *
     * @return RefundRetrievedInterface
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @throws InvalidArgumentException
     */
    public function getRefund(string $refundId): RefundRetrievedInterface
    {
        $data = $this->getApiFactory()->paymentsApi()->retrieveRefund($this->getId(), $refundId);

        return $this->make(RefundRetrievedInterface::class, $data);
    }

    /**
     * @return RefundRetrievedInterface[]
     * @throws SignerException
     * @throws ApiRequestJsonSerializationException
     * @throws ApiResponseUnsuccessfulException
     *
     * @throws InvalidArgumentException
     */
    public function getRefunds(): array
    {
        $data = $this->getApiFactory()->paymentsApi()->retrieveRefunds($this->getId());

        return $this->makeMany(RefundRetrievedInterface::class, $data);
    }
}
