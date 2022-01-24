<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use DateTimeInterface;
use TrueLayer\Interfaces\Payment\PaymentExecutedInterface;
use TrueLayer\Interfaces\Payment\SourceOfFundsInterface;
use TrueLayer\Validation\ValidType;

final class PaymentExecuted extends _PaymentWithAuthorizationConfig implements PaymentExecutedInterface
{
    /**
     * @var DateTimeInterface
     */
    protected DateTimeInterface $executedAt;

    /**
     * @var SourceOfFundsInterface
     */
    protected SourceOfFundsInterface $sourceOfFunds;

    /**
     * @return array
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'executed_at' => DateTimeInterface::class,
            'source_of_funds' => SourceOfFundsInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'executed_at',
            'source_of_funds',
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'executed_at' => 'required|date',
            'source_of_funds' => ['required', ValidType::of(SourceOfFundsInterface::class)],
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
}
