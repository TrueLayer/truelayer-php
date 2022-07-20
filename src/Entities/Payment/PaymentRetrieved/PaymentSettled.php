<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use DateTimeInterface;
use TrueLayer\Exceptions\ApiRequestJsonSerializationException;
use TrueLayer\Exceptions\ApiResponseUnsuccessfulException;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\SignerException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Interfaces\HasApiFactoryInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Interfaces\Payment\RefundRequestInterface;
use TrueLayer\Interfaces\Payment\RefundRetrievedInterface;
use TrueLayer\Traits\ProvidesApiFactory;
use TrueLayer\Validation\ValidType;

final class PaymentSettled extends _PaymentWithAuthorizationConfig implements PaymentSettledInterface, HasApiFactoryInterface
{
    use ProvidesApiFactory;

    /**
     * @var PaymentSourceInterface
     */
    protected PaymentSourceInterface $paymentSource;

    /**
     * @var DateTimeInterface
     */
    protected DateTimeInterface $executedAt;

    /**
     * @var DateTimeInterface
     */
    protected DateTimeInterface $settledAt;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'executed_at' => DateTimeInterface::class,
            'settled_at' => DateTimeInterface::class,
            'payment_source' => PaymentSourceInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'payment_source' => [ValidType::of(PaymentSourceInterface::class)],
            'executed_at' => 'required|date',
            'settled_at' => 'required|date',
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'payment_source',
            'executed_at',
            'settled_at',
        ]);
    }

    /**
     * @return DateTimeInterface
     */
    public function getExecutedAt(): DateTimeInterface
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
     * @return DateTimeInterface
     */
    public function getSettledAt(): DateTimeInterface
    {
        return $this->settledAt;
    }

    /**
     * @return RefundRequestInterface
     * @throws ValidationException
     *
     * @throws InvalidArgumentException
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
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     *
     * @throws ApiRequestJsonSerializationException
     */
    public function getRefund(string $refundId): RefundRetrievedInterface
    {
        $data = $this->getApiFactory()->paymentsApi()->retrieveRefund($this->getId(), $refundId);

        return $this->make(RefundRetrievedInterface::class, $data);
    }

    /**
     * @return RefundRetrievedInterface[]
     * @throws ApiResponseUnsuccessfulException
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws ValidationException
     *
     * @throws ApiRequestJsonSerializationException
     */
    public function getRefunds(): array
    {
        $data = $this->getApiFactory()->paymentsApi()->retrieveRefunds($this->getId());

        return $this->makeMany(RefundRetrievedInterface::class, $data);
    }
}
