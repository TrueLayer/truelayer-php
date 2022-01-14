<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use Illuminate\Support\Carbon;
use TrueLayer\Contracts\Payment\PaymentExecutedInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Services\Util\Type;

final class PaymentExecuted extends _PaymentWithAuthorizationConfig implements PaymentExecutedInterface
{
    /**
     * @var Carbon
     */
    protected Carbon $executedAt;

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'executed_at',
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return \array_merge(parent::rules(), [
            'executed_at' => 'required|date',
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

        return parent::fill($data);
    }
}
