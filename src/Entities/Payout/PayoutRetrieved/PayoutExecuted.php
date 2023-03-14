<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout\PayoutRetrieved;

use TrueLayer\Entities\Payout\PayoutRetrieved;
use TrueLayer\Interfaces\Payout\PayoutExecutedInterface;

final class PayoutExecuted extends PayoutRetrieved implements PayoutExecutedInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $executedAt;

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
    protected function casts(): array
    {
        return \array_merge(parent::casts(), [
            'executed_at' => \DateTimeInterface::class,
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
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface
    {
        return $this->executedAt;
    }
}
