<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\BeneficiaryTypes;
use TrueLayer\Contracts\Models\BeneficiaryInterface;

class MerchantAccountBeneficiary implements BeneficiaryInterface
{
    /**
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return MerchantAccountBeneficiary
     */
    public function id(string $id = null): MerchantAccountBeneficiary
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return MerchantAccountBeneficiary
     */
    public function name(string $name = null): MerchantAccountBeneficiary
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return BeneficiaryTypes::MERCHANT_ACCOUNT;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data): self
    {
        return (new self())
            ->id($data['id'])
            ->name($data['name']);
    }
}
