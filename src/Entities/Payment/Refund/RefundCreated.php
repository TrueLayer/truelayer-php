<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\Refund;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\RefundCreatedInterface;

final class RefundCreated extends Entity implements RefundCreatedInterface
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
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'id' => 'required|string',
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
