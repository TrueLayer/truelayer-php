<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use Illuminate\Support\Carbon;
use TrueLayer\Contracts\Payment\PaymentSettledInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Services\Util\Type;

final class PaymentSettled extends _PaymentWithAuthorizationConfig implements PaymentSettledInterface
{
    /**
     * @var Carbon
     */
    protected Carbon $executedAt;

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

        return parent::fill($data);
    }
}
