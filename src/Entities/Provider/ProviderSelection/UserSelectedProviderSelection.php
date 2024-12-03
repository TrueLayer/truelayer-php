<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\ProviderSelection;

use TrueLayer\Constants\ProviderSelectionTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\Scheme\SchemeSelectionInterface;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Interfaces\Provider\UserSelectedProviderSelectionInterface;

final class UserSelectedProviderSelection extends Entity implements UserSelectedProviderSelectionInterface
{
    /**
     * @var ProviderFilterInterface
     */
    protected ProviderFilterInterface $filter;

    /**
     * @var SchemeSelectionInterface
     */
    protected SchemeSelectionInterface $schemeSelection;

    /**
     * @var mixed[]
     */
    protected array $casts = [
        'filter' => ProviderFilterInterface::class,
        'scheme_selection' => SchemeSelectionInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'filter',
        'scheme_selection',
    ];

    /**
     * @return ProviderFilterInterface|null
     */
    public function getFilter(): ?ProviderFilterInterface
    {
        return $this->filter ?? null;
    }

    /**
     * @param ProviderFilterInterface $filter
     *
     * @return $this
     */
    public function filter(ProviderFilterInterface $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return SchemeSelectionInterface|null
     */
    public function getSchemeSelection(): ?SchemeSelectionInterface
    {
        return $this->schemeSelection ?? null;
    }

    /**
     * @param SchemeSelectionInterface $schemeSelection
     *
     * @return $this
     */
    public function schemeSelection(SchemeSelectionInterface $schemeSelection): self
    {
        $this->schemeSelection = $schemeSelection;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ProviderSelectionTypes::USER_SELECTED;
    }
}
