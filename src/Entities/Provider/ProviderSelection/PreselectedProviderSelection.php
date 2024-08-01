<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\ProviderSelection;

use TrueLayer\Constants\ProviderSelectionTypes;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Provider\PreselectedProviderSelectionInterface;
use TrueLayer\Interfaces\Scheme\SchemeSelectionInterface;

final class PreselectedProviderSelection extends Entity implements PreselectedProviderSelectionInterface
{
    /**
     * @var string
     */
    protected string $providerId;

    /**
     * @var SchemeSelectionInterface
     */
    protected SchemeSelectionInterface $schemeSelection;

    /**
     * @var mixed[]
     */
    protected array $casts = [
        'scheme_selection' => SchemeSelectionInterface::class,
    ];

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
        'provider_id',
        'scheme_selection'
    ];

    /**
     * @return string
     */
    public function getProviderId(): string
    {
        return $this->providerId;
    }

    /**
     * @param string $providerId
     *
     * @return $this
     */
    public function providerId(string $providerId): self
    {
        $this->providerId = $providerId;

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
        return ProviderSelectionTypes::PRESELECTED;
    }
}
