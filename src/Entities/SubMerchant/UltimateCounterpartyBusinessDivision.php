<?php

declare(strict_types=1);

namespace TrueLayer\Entities\SubMerchant;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\SubMerchant\UltimateCounterpartyBusinessDivisionInterface;

final class UltimateCounterpartyBusinessDivision extends Entity implements UltimateCounterpartyBusinessDivisionInterface
{
    /**
     * @var string
     */
    protected string $type = 'business_division';

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
        'type',
        'id',
        'name',
    ];

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type ?? null;
    }

    /**
     * @param string $type
     *
     * @return UltimateCounterpartyBusinessDivisionInterface
     */
    public function type(string $type): UltimateCounterpartyBusinessDivisionInterface
    {
        $this->type = $type;

        return $this;
    }

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
     * @return UltimateCounterpartyBusinessDivisionInterface
     */
    public function id(string $id): UltimateCounterpartyBusinessDivisionInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @param string $name
     *
     * @return UltimateCounterpartyBusinessDivisionInterface
     */
    public function name(string $name): UltimateCounterpartyBusinessDivisionInterface
    {
        $this->name = $name;

        return $this;
    }
}