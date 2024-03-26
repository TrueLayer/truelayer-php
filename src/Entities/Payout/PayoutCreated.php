<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payout;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payout\PayoutCreatedInterface;

final class PayoutCreated extends Entity implements PayoutCreatedInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
    ];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
