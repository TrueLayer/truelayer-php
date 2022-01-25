<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use DateTimeInterface;
use TrueLayer\Interfaces\Payment\PaymentSettledInterface;
use TrueLayer\Interfaces\Payment\SourceOfFundsInterface;
use TrueLayer\Validation\ValidType;

final class PaymentSettled extends _PaymentWithAuthorizationConfig implements PaymentSettledInterface
{
    /**
     * @var SourceOfFundsInterface
     */
    protected SourceOfFundsInterface $sourceOfFunds;

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
            'source_of_funds' => SourceOfFundsInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'source_of_funds' => ['required', ValidType::of(SourceOfFundsInterface::class)],
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
            'source_of_funds',
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
     * @return SourceOfFundsInterface
     */
    public function getSourceOfFunds(): SourceOfFundsInterface
    {
        return $this->sourceOfFunds;
    }

    /**
     * @return DateTimeInterface
     */
    public function getSettledAt(): DateTimeInterface
    {
        return $this->settledAt;
    }
}
