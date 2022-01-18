<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use Illuminate\Support\Carbon;
use TrueLayer\Contracts\Payment\PaymentSettledInterface;
use TrueLayer\Contracts\Payment\SourceOfFundsInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Services\Util\Type;
use TrueLayer\Validation\ValidType;

final class PaymentSettled extends _PaymentWithAuthorizationConfig implements PaymentSettledInterface
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
     * @var Carbon
     */
    protected Carbon $settledAt;

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'source_of_funds' => [ 'required', ValidType::of(SourceOfFunds::class)],
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
     * @return Carbon
     */
    public function getSettledAt(): Carbon
    {
        return $this->settledAt;
    }

    /**
     * @param mixed[] $data
     *
     * @throws InvalidArgumentException
     * @throws ValidationException
     *
     * @return $this
     */
    public function fill(array $data): self
    {
        $data['executed_at'] = Type::getNullableDate($data, 'executed_at');
        $data['settled_at'] = Type::getNullableDate($data, 'settled_at');

        if ($sourceOfFunds = Type::getNullableArray($data, 'source_of_funds')) {
            $data['source_of_funds'] = SourceOfFunds::make($this->getSdk())->fill($sourceOfFunds);
        }

        return parent::fill($data);
    }
}
