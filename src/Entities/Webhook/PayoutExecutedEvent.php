<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Webhook;

use TrueLayer\Interfaces\Webhook\PayoutExecutedEventInterface;

class PayoutExecutedEvent extends PayoutEvent implements PayoutExecutedEventInterface
{
    /**
     * @var \DateTimeInterface
     */
    protected \DateTimeInterface $executedAt;

    /**
     * @var string
     */
    protected string $schemeId;

    /**
     * @return mixed[]
     */
    protected function casts(): array
    {
        return \array_merge_recursive(parent::casts(), [
            'executed_at' => \DateTimeInterface::class,
        ]);
    }

    /**
     * @return mixed[]
     */
    protected function arrayFields(): array
    {
        return \array_merge(parent::arrayFields(), [
            'executed_at',
            'scheme_id',
        ]);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExecutedAt(): \DateTimeInterface
    {
        return $this->executedAt;
    }

    /**
     * @return string|null
     */
    public function getSchemeId(): ?string
    {
        return $this->schemeId ?? null;
    }
}
