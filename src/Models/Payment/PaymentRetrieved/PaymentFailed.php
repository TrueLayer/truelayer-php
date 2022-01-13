<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved;

use Illuminate\Support\Carbon;
use TrueLayer\Constants\PaymentStatus;
use TrueLayer\Contracts\Payment\PaymentFailedInterface;
use TrueLayer\Exceptions\InvalidArgumentException;
use TrueLayer\Exceptions\ValidationException;
use TrueLayer\Models\Model;
use TrueLayer\Models\Payment\PaymentRetrieved;
use TrueLayer\Services\Util\Type;
use TrueLayer\Validation\AllowedConstant;

final class PaymentFailed extends _PaymentWithAuthorizationConfig implements PaymentFailedInterface
{
    /**
     * @var Carbon
     */
    protected Carbon $failedAt;

    /**
     * @var string
     */
    protected string $failureStage;

    /**
     * @var string
     */
    protected string $failureReason;

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return array_merge(parent::arrayFields(), [
            'failed_at',
            'failure_stage',
            'failure_reason'
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'failed_at' => 'required|date',
            'failure_stage' => ['required', AllowedConstant::in(PaymentStatus::class)],
            'failure_reason' => 'nullable|string',
        ]);
    }

    /**
     * @return Carbon
     */
    public function getFailedAt(): Carbon
    {
        return $this->failedAt;
    }

    /**
     * @return string
     */
    public function getFailureStage(): string
    {
        return $this->failureStage;
    }

    /**
     * @return string
     */
    public function getFailureReason(): string
    {
        return $this->failureReason;
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
        $data['failed_at'] = Type::getNullableDate($data, 'failed_at');

        return parent::fill($data);
    }
}
