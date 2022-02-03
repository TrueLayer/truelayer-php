<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use DateTimeInterface;
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;
use TrueLayer\Validation\ValidType;

final class PaymentExecuted extends _PaymentWithAuthorizationConfig implements PaymentExecutedInterface
{
    /**
     * @var DateTimeInterface
     */
    protected DateTimeInterface $executedAt;

    /**
     * @var PaymentSourceInterface
     */
    protected PaymentSourceInterface $paymentSource;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'executed_at' => DateTimeInterface::class,
            'payment_source' => PaymentSourceInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'executed_at',
            'payment_source',
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'executed_at' => 'required|date',
            'payment_source' => ['required', ValidType::of(PaymentSourceInterface::class)],
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
}
