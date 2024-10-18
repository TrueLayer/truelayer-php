<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Refund;

use TrueLayer\Attributes\Field;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\RefundCreatedInterface;

final class RefundCreated extends Entity implements RefundCreatedInterface
{
    /**
     * @var string
     */
    #[Field]
    protected string $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
