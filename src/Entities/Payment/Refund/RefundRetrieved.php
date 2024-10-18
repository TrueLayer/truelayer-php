<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Refund;

use TrueLayer\Attributes\Field;
use TrueLayer\Constants\RefundStatus;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\RefundRetrievedInterface;

class RefundRetrieved extends Entity implements RefundRetrievedInterface
{
    /**
     * @var string
     */
    #[Field]
    protected string $id;

    /**
     * @var int
     */
    #[Field]
    protected int $amountInMinor;

    /**
     * @var string
     */
    #[Field]
    protected string $currency;

    /**
     * @var string
     */
    #[Field]
    protected string $reference;

    /**
     * @var string
     */
    #[Field]
    protected string $status;

    /**
     * @var \DateTimeInterface
     */
    #[Field]
    protected \DateTimeInterface $createdAt;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAmountInMinor(): int
    {
        return $this->amountInMinor;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->getStatus() === RefundStatus::PENDING;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->getStatus() === RefundStatus::AUTHORIZED;
    }

    /**
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->getStatus() === RefundStatus::EXECUTED;
    }

    /**
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->getStatus() === RefundStatus::FAILED;
    }
}
