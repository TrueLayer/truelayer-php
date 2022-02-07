<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use DateTimeInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Validation\ValidType;

final class PaymentSettled extends _PaymentWithAuthorizationConfig implements PaymentSettledInterface
{
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
}
