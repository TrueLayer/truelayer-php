<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\SubMerchants;

use TrueLayer\Constants\UltimateCounterpartyTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\SubMerchants\BusinessDivisionUltimateCounterpartyInterface;

final class BusinessDivisionUltimateCounterparty extends Entity implements BusinessDivisionUltimateCounterpartyInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'name',
        'type',
    ];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return UltimateCounterpartyTypes::BUSINESS_DIVISION;
    }
}
