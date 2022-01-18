<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use Illuminate\Support\Carbon;
use TrueLayer\Contracts\Payment\PaymentExecutedInterface;
use TrueLayer\Contracts\Payment\SourceOfFundsInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Services\Util\Type;
use TrueLayer\Validation\ValidType;

final class PaymentExecuted extends _PaymentWithAuthorizationConfig implements PaymentExecutedInterface
{
    /**
     * @var Carbon
     */
    protected Carbon $executedAt;

    /**
     * @var SourceOfFundsInterface
     */
    protected SourceOfFundsInterface $sourceOfFunds;

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
            'source_of_funds' => ['required', ValidType::of(SourceOfFunds::class)],
        ]);
    }

    /**
     * @return Carbon
     */
    public function getExecutedAt(): Carbon
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
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return PaymentExecuted
     */
    public function fill(array $data): self
    {
        $data['executed_at'] = Type::getNullableDate($data, 'executed_at');

        if ($sourceOfFunds = Type::getNullableArray($data, 'source_of_funds')) {
            $data['source_of_funds'] = SourceOfFunds::make($this->getSdk())->fill($sourceOfFunds);
        }

        return parent::fill($data);
    }
}
