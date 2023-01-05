<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\PaymentUserRetrievedInterface;

final class PaymentUserRetrieved extends Entity implements PaymentUserRetrievedInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * @param string $id
     *
     * @return PaymentUserRetrievedInterface
     */
    public function id(string $id): PaymentUserRetrievedInterface
    {
        $this->id = $id;

        return $this;
    }
}