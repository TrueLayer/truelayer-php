<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\PaymentRetrieved;

use TrueLayer\Attributes\ArrayShape;
use TrueLayer\Attributes\Field;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\AccountIdentifier\AccountIdentifierInterface;
use TrueLayer\Interfaces\Payment\PaymentSourceInterface;

final class PaymentSource extends Entity implements PaymentSourceInterface
{
    /**
     * @var string
     */
    #[Field]
    protected string $id;

    /**
     * @var AccountIdentifierInterface[]
     */
    #[Field, ArrayShape(AccountIdentifierInterface::class)]
    protected array $accountIdentifiers;

    /**
     * @var string
     */
    #[Field]
    protected string $accountHolderName;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * @return AccountIdentifierInterface[]
     */
    public function getAccountIdentifiers(): array
    {
        return $this->accountIdentifiers ?? [];
    }

    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName ?? null;
    }
}
